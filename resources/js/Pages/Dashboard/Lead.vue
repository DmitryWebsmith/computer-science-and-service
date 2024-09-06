<script>
export default {
  name: "Lead",
  props: {
    leadProps: Object,
  },
  data() {
    return {
      leadStatus: String,
      lead: Object,

      editLeadDialog: false,

      leadFirstNameErrorMessage: null,
      leadLastNameErrorMessage: null,
      leadEmailErrorMessage: null,
      leadPhoneErrorMessage: null,
      leadMessageErrorMessage: null,

      leadId: null,
      leadFirstName: null,
      leadLastName: null,
      leadEmail: null,
      leadPhone: null,
      leadMessage: null,
    }
  },
  mounted() {
    this.lead = this.leadProps
    this.leadStatus = this.lead.status
  },
  methods: {
    deleteLead(leadId) {
      let data = {
        lead_id: leadId,
      }

      axios.post(route('dashboard.lead.destroy'), data)
          .then((res) => {
            if (res.status === 200) {
              this.$emit('updateParent', res.data.leads)
            }
          })
          .catch((error) => {
            if (error.response.data.message) {
              alert(error.response.data.message)
            }
          });
    },
    openEditLeadDialog(lead) {
      this.leadId = lead.id
      this.leadFirstName = lead.first_name
      this.leadLastName = lead.last_name
      this.leadEmail = lead.email
      this.leadPhone = lead.phone
      this.leadMessage = lead.message
      this.editLeadDialog = true
    },
    validEmail: function (email) {
      let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    },
    sendLeadInfo() {
      let errorsCount = 0

      if (!this.leadFirstName) {
        this.leadFirstNameErrorMessage = "Пожалуйста, введите имя"
        errorsCount++
      }

      if (!this.leadLastName) {
        this.leadLastNameErrorMessage = "Пожалуйста, введите фамилию"
        errorsCount++
      }

      if (!this.validEmail(this.leadEmail)) {
        this.leadEmailErrorMessage = "Неверный емайл"
        errorsCount++
      }

      if (!this.leadPhone) {
        this.leadPhoneErrorMessage = "Пожалуйста, введите номер телефона"
        errorsCount++
      }

      if (!this.leadMessage) {
        this.leadMessageErrorMessage = "Пожалуйста, напишите сообщение"
        errorsCount++
      }

      if (errorsCount > 0) {
        return false
      }

      let data = {
        id: this.leadId,
        first_name: this.leadFirstName,
        last_name: this.leadLastName,
        email: this.leadEmail,
        phone: this.leadPhone,
        message: this.leadMessage
      }

      axios.post(route('dashboard.lead.edit'), data)
          .then((res) => {
            if (res.status === 200) {
              this.$emit('updateParent', res.data.leads)
              this.editLeadDialog = false
            }
          })
          .catch((error) => {
            if (error.response.data.message) {
              alert(error.response.data.message)
            }
          });
    }
  },
  watch: {
    leadFirstName(newVal, oldVal) {
      this.leadFirstNameErrorMessage = null
    },
    leadLastName(newVal, oldVal) {
      this.leadLastNameErrorMessage = null
    },
    leadEmail(newVal, oldVal) {
      this.leadEmailErrorMessage = null
    },
    leadPhone(newVal, oldVal) {
      this.leadPhoneErrorMessage = null
    },
    leadMessage(newVal, oldVal) {
      this.leadMessageErrorMessage = null
    },
    leadStatus(newVal, oldVal) {
      if (newVal === oldVal) {
        return 0
      }
      axios.post(route('dashboard.lead.edit.status'), {
        status: newVal,
        id: this.lead.id
      })
    }
  }
}
</script>

<template>
  <v-col class="d-flex justify-start">
    <v-sheet
        class="pa-3"
        elevation="3"
        max-width="800px"
        width="100%"
        rounded
    >
      <v-container class="justify-start ml-1">

        <v-row dense>
          <v-col class="justify-start ml-1" cols="1">
            Id: {{ lead.id }}
          </v-col>
          <v-col class="justify-start ml-1" cols="3">
            Имя: {{ lead.first_name }}
          </v-col>
          <v-col class="justify-start ml-1" cols="3">
            Фамилия: {{ lead.last_name }}
          </v-col>
          <v-col class="justify-start ml-1" cols="4">
            Телефон: {{ lead.phone }}
          </v-col>
        </v-row>

        <v-row>
          <v-col class="justify-start ml-1 mb-2" cols="12">
            {{ lead.message }}
          </v-col>
        </v-row>

        <v-row dense>
          <v-col class="justify-start ml-1" cols="2">
            <v-btn
                color="red"
                class="text-none"
                @click="deleteLead(lead.id)"
            >
              Удалить
            </v-btn>
          </v-col>
          <v-col class="justify-start ml-1" cols="3">
            <v-btn
                color="green"
                class="text-none"
                @click="openEditLeadDialog(lead)"
            > Редактировать </v-btn>
          </v-col>
          <v-col class="justify-start ml-1" cols="6">
            <v-radio-group
                v-model="leadStatus"
                inline
            >
              <v-radio
                  label="Новый"
                  value="new"
              ></v-radio>
              <v-radio
                  label="В работе"
                  value="at_work"
              ></v-radio>
              <v-radio
                  label="Завершен"
                  value="completed"
              ></v-radio>
            </v-radio-group>
          </v-col>
        </v-row>

      </v-container>

    </v-sheet>
  </v-col>
  <v-dialog
      v-model="editLeadDialog"
      max-width="400"
  >
    <v-card
        prepend-icon="mdi-account"
        title="Форма обращения клиента"
    >
      <v-card-text>
        <v-row dense>
          <v-col
              cols="12"
          >
            <v-text-field
                label="Id*"
                required
                v-model="leadId"
                disabled
                variant="solo"
            ></v-text-field>
          </v-col>
          <v-col
              cols="12"
          >
            <v-text-field
                label="Имя*"
                required
                :error-messages="leadFirstNameErrorMessage"
                v-model="leadFirstName"
                variant="solo"
            ></v-text-field>
          </v-col>

          <v-col
              cols="12"
          >
            <v-text-field
                label="Фамилия*"
                required
                :error-messages="leadLastNameErrorMessage"
                v-model="leadLastName"
                variant="solo"
            ></v-text-field>
          </v-col>

          <v-col
              cols="12"
          >
            <v-text-field
                label="Email*"
                required
                :error-messages="leadEmailErrorMessage"
                v-model="leadEmail"
                variant="solo"
            ></v-text-field>
          </v-col>

          <v-col
              cols="12"
          >
            <v-text-field
                label="Номер телефона*"
                required
                :error-messages="leadPhoneErrorMessage"
                v-model="leadPhone"
                variant="solo"
            ></v-text-field>
          </v-col>

          <v-col
              cols="12"
          >
            <v-textarea
                rows="4"
                max-rows="8"
                label="Сообщение*"
                auto-grow
                hide-details
                :error-messages="leadMessageErrorMessage"
                v-model="leadMessage">
            </v-textarea>
          </v-col>


        </v-row>

        <small class="text-caption text-medium-emphasis">* обязательное поле</small>
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>

        <v-btn
            text="Закрыть"
            class="text-none"
            @click="editLeadDialog = false"
        ></v-btn>

        <v-btn
            color="primary"
            class="text-none"
            text="Редактировать"
            @click="sendLeadInfo()"
        ></v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<style scoped>

</style>