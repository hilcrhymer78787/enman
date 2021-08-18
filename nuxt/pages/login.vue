<template>
  <v-card max-width="350" class="login-card mx-auto">
    <v-card-title>ログイン</v-card-title>
    <v-card-text>
      <v-alert color="red" text v-show="error">
          ユーザー名かパスワードが異なります。
      </v-alert>
      <v-form>
          <v-text-field
          placeholder="ユーザー名"
          prepend-inner-icon="mdi-account-circle"
          outlined
          v-model="form.username"
          ></v-text-field>

          <v-text-field
          placeholder="パスワード"
          prepend-inner-icon="mdi-lock"
          :append-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'"
          :type="passwordShow ? 'text' : 'password'"
          outlined
          v-model="form.password"
          @click:append="passwordShow = !passwordShow"
          ></v-text-field>
          
          <v-btn block x-large @click="login">ログイン</v-btn>
      </v-form>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  layout: 'login',
  data () {
    return {
      form: {
        username: '',
        password: '',
      },
      passwordShow: false,
      error: false
    }
  },
  methods: {
    async login() {
      this.error = false;
    //   try {
    //     await this.$auth.loginWith('local', { data: this.form });
    //   } catch(error) {
    //     this.error = true;
    //   }
    }
  }
}
</script>