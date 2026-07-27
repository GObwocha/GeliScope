<script setup>
import { ref } from 'vue';
import { useTheme } from 'vuetify';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const theme = useTheme();
const router = useRouter();
const authStore = useAuthStore();

const step = ref(1);
const email = ref('');
const password = ref('');
const showPassword = ref(false);
const loading = ref(false);
const errorMsg = ref('');
const userData = ref(null);

const rules = {
  required: value => !!value || 'Required.',
  min: v => v.length >= 8 || 'Min 8 characters',
}

const handleCheckEmail = async () => {
  if (!email.value) return;
  
  loading.value = true;
  errorMsg.value = '';
  try {
    const data = await authStore.checkEmail(email.value);
    userData.value = data;
    step.value = 2;
  } catch (error) {
    errorMsg.value = error.response?.data?.message || 'User not found.';
  } finally {
    loading.value = false;
  }
}

const handleLogin = async () => {
  if (!password.value) return;
  
  loading.value = true;
  errorMsg.value = '';
  try {
    await authStore.login(email.value, password.value);
    router.push('/dashboard'); // Or the active role dashboard
  } catch (error) {
    errorMsg.value = error.response?.data?.message || 'Incorrect password.';
  } finally {
    loading.value = false;
  }
}

const goBack = () => {
  step.value = 1;
  password.value = '';
  errorMsg.value = '';
}
</script>

<template>
    <v-container class="fill-height d-flex align-center justify-center">
        <v-card 
        :color="theme.global.current.value.dark ? 'surface': 'transparent'" 
        width="100%"
        max-width="400"
        class="text-center pa-4"
        >
            <!--Header-->
            <div class="d-flex align-center justify-center mb-4">
              <v-btn v-if="step === 2" icon="mdi-arrow-left" variant="text" size="small" class="position-absolute etyle="left: 16px;" @click="goBack"></v-btn>
              <h1 class="mb-0">{{ step === 1 ? 'Login' : 'Welcome back' }}</h1>
            </div>

            <!-- Error Message -->
            <v-alert v-if="errorMsg" type="error" variant="tonal" class="mb-4 text-caption" density="compact">{{ errorMsg }}</v-alert>

            <!--Form Step 1: Email-->
            <template v-if="step === 1">
              <v-text-field 
                variant="outlined"
                density="comfortable"
                class="mb-4"
                placeholder="Email address"
                v-model="email"
                :rules="[rules.required]"
                @keyup.enter="handleCheckEmail"
              ></v-text-field>

              <v-btn
                size="large"
                block 
                color="primary"
                class="text-none mb-6"
                :loading="loading"
                @click="handleCheckEmail"
              >Continue</v-btn>
            </template>

            <!--Form Step 2: Password-->
            <template v-if="step === 2">
              <div class="d-flex align-center justify-center mb-4">
                <v-avatar color="primary" size="48" class="mr-3 font-weight-bold shadow-glow">
                  <span class="text-white">{{ userData?.first_name[0] }}{{ userData?.last_name[0] }}</span>
                </v-avatar>
                <div class="text-left">
                  <div class="text-body-1 font-weight-bold">{{ userData?.first_name }} {{ userData?.last_name }}</div>
                  <div class="text-caption text-medium-emphasis">{{ userData?.email }}</div>
                </div>
              </div>

              <v-text-field 
                variant="outlined"
                density="comfortable"
                class="mb-4"
                placeholder="Password"
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                @click:append-inner="showPassword = !showPassword"
                :rules="[rules.required]"
                @keyup.enter="handleLogin"
              ></v-text-field>

              <v-btn
                size="large"
                block 
                color="primary"
                class="text-none mb-6"
                :loading="loading"
                @click="handleLogin"
              >Sign In</v-btn>
            </template>

            <template v-if="step === 1">
              <!-- Divider -->
              <v-divider>or continue with</v-divider>

              <!-- Google button -->
              <v-btn
                prepend-icon="mdi-google"
                block
                color="black"
                size="large"
                variant="flat"
                class="text-none mb-8 mt-4"
              >
                  Google
              </v-btn>
            </template>
            
            <!--Form footer-->
            <p class="text-caption text-medium-emphasis mb-4">
                By clicking continue, you agree to our 
                <a class="text-decoration-none text-high-emphasis font-weight-medium" href="#">Terms of Services</a>
                and 
                <a class="text-decoration-none text-high-emphasis font-weight-medium" href="#">Privacy Policy</a>
            </p>

            <div class="text-body-2" v-if="step === 1">
                Don't have an account? 
                <router-link to="/register" class="text-primary font-weight-bold text-decoration-none">Sign up</router-link>
            </div>

        </v-card>       
    </v-container>
</template>

<style scoped>
.extracted-style-1 {
  left: 16px;
}
</style>
