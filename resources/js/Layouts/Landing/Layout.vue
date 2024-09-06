<script>
import { router } from '@inertiajs/vue3'
export default {
    name: "Layout",
    data() {
        return {
            theme: 'light',
            loginDialog: false,
            registerDialog: false,

            loginEmail: null,
            loginPassword: null,
            loginRemember: false,
            loginEmailErrorMessage: null,
            loginPasswordErrorMessage: null,

            registerOrganizationName: null,
            registerName: null,
            registerEmail: null,
            registerPassword: null,
            registerPasswordConfirmation: null,

            registerOrganizationNameErrorMessage: null,
            registerNameErrorMessage: null,
            registerEmailErrorMessage: null,
            registerPasswordErrorMessage: null,
            registerPasswordConfirmationErrorMessage: null,

            passwordRestoreDialog: false,
            passwordRestoreEmail: null,
            passwordRestoreEmailErrorMessage: null,
            passwordResetButtonDisabled: false
        }
    },
    methods: {
        validEmail: function (email) {
            let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        login() {
            let errorsCount = 0

            if (!this.loginEmail) {
                this.loginEmailErrorMessage = "Пожалуйста, введите Емайл"
                errorsCount++
            }

            if (!this.loginPassword) {
                this.loginPasswordErrorMessage = "Пожалуйста, введите пароль"
                errorsCount++
            }

            if (!this.validEmail(this.loginEmail)) {
                this.loginEmailErrorMessage = "Неверный Емайл"
                errorsCount++
            }

            if (errorsCount > 0) {
                return false
            }

            let data = {
                email: this.loginEmail,
                password: this.loginPassword,
                remember: this.loginRemember,
            }

            axios.post(route('login'), data)
                .then((res) => {
                    if (res.status === 200) {
                        router.reload()
                    }
                })
                .catch((error) => {
                    if (error.response.data.message) {
                        alert(error.response.data.message)
                    }
                });
        },
        register() {
            let errorsCount = 0

            if (!this.registerName) {
                this.registerNameErrorMessage = "Пожалуйста, введите Имя"
                errorsCount++
            }

            if (!this.registerEmail) {
                this.registerEmailErrorMessage = "Пожалуйста, введите Емайл"
                errorsCount++
            }

            if (!this.registerPassword) {
                this.registerPasswordErrorMessage = "Пожалуйста, введите пароль"
                errorsCount++
            }

            if (this.registerPassword.length < 8) {
                this.registerPasswordErrorMessage = "Длина пароля должна быть не менее 8 символов"
                errorsCount++
            }

            if (!this.registerPasswordConfirmation) {
                this.registerPasswordConfirmationErrorMessage = "Пожалуйста, введите пароль"
                errorsCount++
            }

            if (this.registerPasswordConfirmation.length < 8) {
                this.registerPasswordConfirmationErrorMessage = "Длина пароля должна быть не менее 8 символов"
                errorsCount++
            }

            if (this.registerPasswordConfirmation !== this.registerPassword) {
                this.registerPasswordErrorMessage = "Пароли не совпадают"
                this.registerPasswordConfirmationErrorMessage = "Пароли не совпадают"
                errorsCount++
            }

            if (!this.validEmail(this.registerEmail)) {
                this.registerEmailErrorMessage = "Неверный Емайл"
                errorsCount++
            }

            if (errorsCount > 0) {
                return false
            }

            let data = {
                name: this.registerName,
                email: this.registerEmail,
                password: this.registerPassword,
                password_confirmation: this.registerPasswordConfirmation
            }

            axios.post(route('register_store'), data)
                .then((res) => {
                    if (res.status === 200) {
                        router.reload()
                    }
                })
                .catch((error) => {
                    if (error.response.data.message) {
                        alert(error.response.data.message)
                    }
                });
        },
        restorePassword(stage) {
            if (stage === 'showPasswordRestoreModal') {
                this.loginDialog = false
                this.passwordRestoreDialog = true
            }
            if (stage === 'sendPasswordRestoreEmail') {
                let errorsCount = 0

                if (!this.passwordRestoreEmail) {
                    this.passwordRestoreEmailErrorMessage = "Пожалуйста, введите Емайл"
                    errorsCount++
                }

                if (!this.validEmail(this.passwordRestoreEmail) && errorsCount === 0) {
                    this.passwordRestoreEmailErrorMessage = "Неверный Емайл"
                    errorsCount++
                }

                if (errorsCount > 0) {
                    return false
                }

                let data = {
                    email: this.passwordRestoreEmail
                }

                this.passwordResetButtonDisabled = true

                axios.post(route('password.restore.link.email'), data)
                    .then((res) => {
                        this.passwordRestoreDialog = false
                        this.passwordResetButtonDisabled = false
                        if (res.data.message) {
                            alert(res.data.message)
                        }
                    })
                    .catch((error) => {
                        this.passwordResetButtonDisabled = false
                        if (error.response.data.message) {
                            this.passwordRestoreEmailErrorMessage = error.response.data.message
                        }
                    });
            }
        }
    },
    watch: {
        loginEmail(newVal, oldVal) {
            this.loginEmailErrorMessage = null
        },
        loginPassword(newVal, oldVal) {
            this.loginPasswordErrorMessage = null
        },
        registerEmail(newVal, oldVal) {
            this.registerEmailErrorMessage = null
        },
        registerPassword(newVal, oldVal) {
            this.registerPasswordErrorMessage = null
        },
        registerPasswordConfirmation(newVal, oldVal) {
            this.registerPasswordConfirmationErrorMessage = null
        },
        registerName() {
            this.registerNameErrorMessage = null
        },
        passwordRestoreEmail() {
            this.passwordRestoreEmailErrorMessage = null
        }
    }
}
</script>

<template>
    <v-responsive class="border rounded">
        <v-app :theme="theme">
            <v-app-bar
                color="#004D40"
                title="Тестовое задание">
                <div>
                    <div class="d-flex justify-end">

                        <div class="d-md-none">
<!--                            hide on screens wider than md-->

                            <div class="ma-1" align="center">
                                <v-menu
                                    min-width="200px"
                                    rounded
                                >
                                    <template v-slot:activator="{ props }">
                                        <v-btn
                                            v-bind="props"
                                        >
                                            Меню
                                        </v-btn>
                                    </template>
                                    <v-card>
                                        <v-card-text>
                                            <div class="text-center">
                                                <v-btn @click="registerDialog = true" class="text-none mt-2">Регистрация</v-btn>
                                                <v-divider></v-divider>
                                                <v-btn @click="loginDialog = true" class="text-none mt-2">Авторизация</v-btn>
                                                <v-divider></v-divider>
                                                <v-btn href="https://i-am-web-artisan.ru/" class="text-none mt-2" target="_blank">Разработчик</v-btn>
                                            </div>
                                        </v-card-text>
                                    </v-card>
                                </v-menu>
                            </div>

                        </div>
                        <!--                            hide on screens smaller than md-->
                        <div class="d-none d-md-block">
                            <v-sheet
                                class="ma-2 pa-2"
                                color="transparent"
                            >
                                <v-btn @click="registerDialog = true" class="text-none">Регистрация</v-btn> |
                                <v-btn @click="loginDialog = true" class="text-none">Авторизация</v-btn> |
                                <v-btn href="https://i-am-web-artisan.ru/" class="text-none" target="_blank">Разработчик</v-btn>
                            </v-sheet>
                        </div>
                    </div>
                </div>
            </v-app-bar>

            <slot />
        </v-app>

        <v-dialog
            v-model="passwordRestoreDialog"
            max-width="400"
        >
            <v-card
                prepend-icon="mdi-account"
                title="Восстановление пароля"
            >
                <v-card-text>
                    <v-row dense>
                        <v-col
                            cols="12"
                        >
                            <v-text-field
                                label="Email*"
                                required
                                :error-messages="passwordRestoreEmailErrorMessage"
                                v-model="passwordRestoreEmail"
                                variant="solo"
                            ></v-text-field>
                        </v-col>

                    </v-row>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn
                        text="Закрыть"
                        class="text-none"
                        rounded="lg"
                        @click="passwordRestoreDialog = false"
                    ></v-btn>

                    <v-btn
                        color="primary"
                        class="text-none"
                        rounded="lg"
                        text="Отправить"
                        :disabled="passwordResetButtonDisabled"
                        @click="restorePassword('sendPasswordRestoreEmail')"
                    ></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="loginDialog"
            max-width="400"
        >
            <v-card
                prepend-icon="mdi-account"
                title="Авторизация"
            >
                <v-card-text>
                    <v-row dense>
                         <v-col
                            cols="12"
                        >
                            <v-text-field
                                label="Email*"
                                required
                                :error-messages="loginEmailErrorMessage"
                                v-model="loginEmail"
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
                                :error-messages="loginPasswordErrorMessage"
                                v-model="loginPassword"
                                variant="solo"
                            ></v-text-field>
                        </v-col>

                    </v-row>

                    <small class="text-caption text-medium-emphasis">* обязательное поле</small>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-btn
                        text="Восстановить пароль"
                        class="text-none"
                        rounded="lg"
                        @click="restorePassword('showPasswordRestoreModal')"
                    ></v-btn>

                    <v-spacer></v-spacer>

                    <v-btn
                        text="Закрыть"
                        class="text-none"
                        rounded="lg"
                        @click="loginDialog = false"
                    ></v-btn>

                    <v-btn
                        color="primary"
                        class="text-none"
                        rounded="lg"
                        text="Войти"
                        @click="login()"
                    ></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="registerDialog"
            max-width="400"
        >
            <v-card
                prepend-icon="mdi-account"
                title="Регистрация"
            >
                <v-card-text>
                    <v-row dense>
                        <v-col
                            cols="12"
                        >
                            <v-text-field
                                label="Имя*"
                                required
                                :error-messages="registerNameErrorMessage"
                                v-model="registerName"
                                variant="solo"
                            ></v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                        >
                            <v-text-field
                                label="Email*"
                                required
                                :error-messages="registerEmailErrorMessage"
                                v-model="registerEmail"
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
                                :error-messages="registerPasswordErrorMessage"
                                v-model="registerPassword"
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
                                :error-messages="registerPasswordConfirmationErrorMessage"
                                v-model="registerPasswordConfirmation"
                                variant="solo"
                            ></v-text-field>
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
                        @click="registerDialog = false"
                    ></v-btn>

                    <v-btn
                        color="primary"
                        class="text-none"
                        text="Регистрация"
                        @click="register()"
                    ></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </v-responsive>
</template>

<style scoped>

</style>
