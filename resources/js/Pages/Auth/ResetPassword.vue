<script>
import {Head, router} from '@inertiajs/vue3';

export default {
    props: {
        token: String,
        email: String
    },
    data() {
        return {
            theme: 'light',
            passwordRestoreConfirmationDialog: false,
            passwordRestoreConfirmationText: null,

            password: null,
            passwordConfirmation: null,
            passwordErrorMessage: null,
            passwordConfirmationErrorMessage: null,
        }
    },
    methods: {
        router() {
            return router
        },
        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light'
        },
        changePassword() {
            let errorsCount = 0

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
                token: this.token,
                email: this.email,
                password: this.password,
                password_confirmation: this.passwordConfirmation
            }

            axios.post(route('password.store'), data)
                .then((res) => {
                    if (res.data.message) {
                        this.passwordRestoreConfirmationText = res.data.message
                        this.passwordRestoreConfirmationDialog = true
                    }
                })
                .catch((error) => {
                    if (error.response.data.message) {
                        alert(error.response.data.message)
                    }
                });
        },
        goTo(url) {
            console.log("url = "+url)
            window.location.href = url
        }
    },
    watch: {
        password(newVal, oldVal) {
            this.passwordErrorMessage = null
        },
        passwordConfirmation(newVal, oldVal) {
            this.confirmationErrorMessage = null
        }
    }
}
</script>

<template>
    <Head title="Восстановление пароля" />
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
                    title="Восстановление пароля"
                    style="width: 400px;"
                >
                    <v-card-text>
                        <v-row dense>

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

                        <small class="text-caption text-medium-emphasis">* обязательное поле</small>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>

                        <v-btn
                            color="primary"
                            class="text-none"
                            text="Изменить пароль"
                            @click="changePassword()"
                        ></v-btn>
                    </v-card-actions>
                </v-card>
            </v-container>
        </v-app>

        <v-dialog
            v-model="passwordRestoreConfirmationDialog"
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
                            {{ passwordRestoreConfirmationText }}
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
                        @click="passwordRestoreConfirmationDialog = false"
                    ></v-btn>

                    <v-btn
                        color="primary"
                        class="text-none"
                        rounded="lg"
                        text="На главную"
                        @click="goTo(route('home'))"
                    ></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </v-responsive>

</template>
