<script>
import {Head, router} from '@inertiajs/vue3';

export default {
    props: {
        uuid: String,
        adminId: Number,
        organizationName: String
    },
    data() {
        return {
            theme: 'light',

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
        }
    },
    mounted() {
        this.registerOrganizationName = this.organizationName
    },
    methods: {
        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light'
        },
        validEmail: function (email) {
            let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
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
                admin_id: this.adminId,
                uuid: this.uuid,
                organization_name: this.organizationName,
                name: this.registerName,
                email: this.registerEmail,
                password: this.registerPassword,
                password_confirmation: this.registerPasswordConfirmation
            }

            axios.post(route('register_operator_store'), data)
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
        }
    },
    watch: {
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
        }
    }
}
</script>

<template>
    <Head title="Регистрация Оператора" />
    <v-responsive class="border rounded">
        <v-app :theme="theme">
            <v-app-bar
                color="#004D40"
                title="Support-Chat.Pro - Чат поддержки для Вашего сайта.">
                <div>
                    <div class="d-flex justify-end">
                        <v-sheet
                            class="ma-2 pa-2"
                            color="transparent"
                        >
                            <v-btn
                                :prepend-icon="theme === 'light' ? 'mdi-weather-sunny' : 'mdi-weather-night'"
                                @click="toggleTheme">
                            </v-btn>
                        </v-sheet>
                    </div>
                </div>
            </v-app-bar>
            <v-container class="fill-height align-center justify-center">
                <v-card
                    prepend-icon="mdi-account"
                    title="Регистрация Оператора"
                    style="width: 400px;"
                >
                    <v-card-text>
                        <v-row dense>
                            <v-col
                                cols="12"
                            >
                                <v-text-field
                                    label="Организация"
                                    :error-messages="registerOrganizationNameErrorMessage"
                                    v-model="registerOrganizationName"
                                    variant="solo"
                                    disabled
                                ></v-text-field>
                            </v-col>

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
                            color="primary"
                            text="Регистрация"
                            @click="register()"
                        ></v-btn>
                    </v-card-actions>
                </v-card>
            </v-container>
        </v-app>

    </v-responsive>

</template>
