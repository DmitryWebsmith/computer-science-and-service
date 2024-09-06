<template>
    <Layout
        :operatorData="operatorData"
    >
        <div style="width: 100%">
            <v-container class="justify-start ml-3">
                <v-row>
                    <v-col class="d-flex justify-start">
                        <v-sheet
                            class="pa-3"
                            elevation="3"
                            max-width="800px"
                            width="100%"
                            rounded
                        >
                            <v-container class="justify-start ml-3">
                                <v-row>
                                    <v-col class="d-flex justify-start">
                                        <h4 class="text-h6 font-weight-bold mb-4">Обновление данных</h4>
                                    </v-col>
                                </v-row>

                                <v-row dense>
                                    <v-col
                                        cols="12"
                                    >
                                        <v-text-field
                                            label="Имя*"
                                            required
                                            :error-messages="nameErrorMessage"
                                            v-model="name"
                                            variant="solo"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                    >
                                        <v-text-field
                                            label="Email*"
                                            required
                                            :error-messages="emailErrorMessage"
                                            v-model="email"
                                            variant="solo"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                    >
                                        <v-text-field
                                            label="Пароль*"
                                            type="password"
                                            required
                                            :error-messages="passwordErrorMessage"
                                            v-model="password"
                                            variant="solo"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                    >
                                        <v-text-field
                                            label="Пароль*"
                                            type="password"
                                            required
                                            :error-messages="passwordConfirmationErrorMessage"
                                            v-model="passwordConfirmation"
                                            variant="solo"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>

                                <v-row class="d-flex justify-start" align="center">
                                    <v-col class="justify-start">
                                        <v-btn
                                            @click="updateProfileData"
                                            class="text-none text-subtitle-1 pa-2"
                                            color="#26A69A"
                                        >
                                            Обновить данные
                                        </v-btn>
                                    </v-col>
                                </v-row>
                            </v-container>

                        </v-sheet>
                    </v-col>
                </v-row>
            </v-container>
        </div>

    </Layout>
</template>

<script>
import Layout from '@/Layouts/Dashboard/Layout.vue';
import {router} from '@inertiajs/vue3'

export default {
  components: {Layout},
  props: {
    operatorData: Object,
  },
  data() {
    return {
      email: null,
      name: null,
      password: null,
      passwordConfirmation: null,
      emailErrorMessage: null,
      nameErrorMessage: null,
      passwordErrorMessage: null,
      passwordConfirmationErrorMessage: null,
    }
  },
  mounted() {
    this.name = this.operatorData.name
    this.email = this.operatorData.email
  },
  methods: {
    validEmail: function (email) {
      let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    },
    updateProfileData() {
      let errorsCount = 0

      if (!this.name) {
          this.nameErrorMessage = "Пожалуйста, введите ваше имя"
          errorsCount++
      }

      if (!this.email) {
          this.emailErrorMessage = "Пожалуйста, введите Email"
          errorsCount++
      }

      if (!this.validEmail(this.email)) {
          this.emailErrorMessage = "Неверный Емайл"
          errorsCount++
      }

      if (!this.password) {
          this.passwordErrorMessage = "Пожалуйста, введите пароль"
          errorsCount++
      }

      if (this.password.length < 8) {
          this.passwordErrorMessage = "Длина пароля должна быть не менее 8 символов"
          errorsCount++
      }

      if (!this.passwordConfirmation) {
          this.passwordConfirmationErrorMessage = "Пожалуйста, введите пароль"
          errorsCount++
      }

      if (this.passwordConfirmation.length < 8) {
          this.passwordConfirmationErrorMessage = "Длина пароля должна быть не менее 8 символов"
          errorsCount++
      }

      if (this.passwordConfirmation !== this.password) {
          this.passwordErrorMessage = "Пароли не совпадают"
          this.passwordConfirmationErrorMessage = "Пароли не совпадают"
          errorsCount++
      }

      if (errorsCount > 0) {
          return false
      }

      let data = {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.passwordConfirmation
      }

      axios.post(route('profile.data.update'), data)
          .then((res) => {
              if (res.status === 200) {
                  router.reload()
                  alert("Данные изменены.")
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
    password(newVal, oldVal) {
      this.passwordErrorMessage = null
    },
    passwordConfirmation(newVal, oldVal) {
      this.passwordConfirmationErrorMessage = null
    },
    name(newVal, oldVal) {
      this.nameErrorMessage = null
    },
    email(newVal, oldVal) {
      this.emailErrorMessage = null
    },
  }
}
</script>
