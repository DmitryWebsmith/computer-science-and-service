<script>
import Layout from '@/Layouts/Landing/Layout.vue';
import {Head, router} from '@inertiajs/vue3'

export default {
    name: "index",
    components: { Layout, Head },

    data() {
        return {
          height: null,

          leadFirstNameErrorMessage: null,
          leadLastNameErrorMessage: null,
          leadEmailErrorMessage: null,
          leadPhoneErrorMessage: null,
          leadMessageErrorMessage: null,

          leadFirstName: null,
          leadLastName: null,
          leadEmail: null,
          leadPhone: null,
          leadMessage: null,
        }
    },
    mounted() {
      window.addEventListener('resize', this.getDimensions);
      this.height = document.documentElement.clientHeight - 100;
    },
    unmounted() {
      window.removeEventListener('resize', this.getDimensions);
    },
    methods: {
      getDimensions() {
        this.height = document.documentElement.clientHeight - 100;
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
          first_name: this.leadFirstName,
          last_name: this.leadLastName,
          email: this.leadEmail,
          phone: this.leadPhone,
          message: this.leadMessage
        }

        axios.post(route('lead.store'), data)
            .then((res) => {
              if (res.status === 200) {
                this.leadFirstName = null
                this.leadLastName = null
                this.leadEmail = null
                this.leadPhone = null
                this.leadMessage = null
                alert("Спасибо за обращение! Ваше сообщение отправлено администрации сайта.")
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
    }
}
</script>

<template>
    <Head>
        <title>Тестовое задание</title>
    </Head>

    <Layout>

        <div style="width: 100%; height: 100%;
        background-image: url('/img/8840374.jpg');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;">
            <v-container>
                <v-row
                    justify="center"
                    no-gutters
                >
                    <v-col
                        style="max-width: 1200px; margin-top: 4rem"
                    >
                        <v-sheet
                            class="ma-0 pa-3 mb-6"
                            elevation="4"
                            width="100%"
                            :style="{'height': height+'px'}"
                            rounded
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
                                  color="primary"
                                  class="text-none"
                                  text="Отправить"
                                  @click="sendLeadInfo()"
                              ></v-btn>
                            </v-card-actions>
                          </v-card>
                        </v-sheet>
                    </v-col>
                </v-row>
            </v-container>
        </div>

    </Layout>
</template>

<style scoped>

</style>
