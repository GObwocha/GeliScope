<script setup>
import { ref, onMounted } from 'vue';
import { useTheme } from 'vuetify';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const theme = useTheme();
const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

const step = ref(1);
const email = ref('');
const password = ref('');
const showPassword = ref(false);
const loading = ref(false);
const loadingGoogle = ref(false);
const errorMsg = ref('');
const otp = ref('');
const userData = ref(null);

const rules = {
  required: value => !!value || 'Required.',
  email: value => {
    const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    return pattern.test(value) || 'Invalid e-mail.'
  },
}

const handleCheckEmail = async () => {
  if (!email.value || rules.email(email.value) !== true) return;
  
  loading.value = true;
  errorMsg.value = '';
  try {
    const data = await authStore.checkEmail(email.value);
    userData.value = data;
    step.value = 2; // Move to password step
  } catch (error) {
    if (error.response?.status === 404) {
      errorMsg.value = "Couldn't find your account";
    } else {
      errorMsg.value = "An error occurred. Please try again.";
    }
  } finally {
    loading.value = false;
  }
}

const handleLogin = async () => {
  if (!password.value) return;
  
  loading.value = true;
  errorMsg.value = '';
  try {
    const res = await authStore.login(email.value, password.value);
    if (res && res.requires_otp) {
        step.value = 3;
    } else {
        router.push('/dashboard');
    }
  } catch (error) {
    errorMsg.value = error.response?.data?.message || 'Incorrect password.';
  } finally {
    loading.value = false;
  }
}

const handleVerifyOtp = async () => {
  if (!otp.value || otp.value.length !== 6) return;
  
  loading.value = true;
  errorMsg.value = '';
  try {
    await authStore.verifyOtp(email.value, password.value, otp.value);
    router.push('/dashboard');
  } catch (error) {
    errorMsg.value = error.response?.data?.message || 'Invalid OTP.';
  } finally {
    loading.value = false;
  }
}

const goBack = () => {
  if (step.value === 3) {
      step.value = 2;
      otp.value = '';
  } else if (step.value === 2) {
      step.value = 1;
      password.value = '';
  }
  errorMsg.value = '';
}

const googleLogin = async () => {
    loadingGoogle.value = true
    await authStore.loginWithGoogle();
    loadingGoogle.value = false
}

const successMsg = ref('');

onMounted(() => {
    if (route.query.error === 'user_not_found') {
        errorMsg.value = 'User not found. Please ensure you are registered.';
        const query = { ...route.query };
        delete query.error;
        router.replace({ path: route.path, query });
    } else if (route.query.registered === 'true') {
        successMsg.value = 'Registration successful! Please check your email to verify your account.';
        const query = { ...route.query };
        delete query.registered;
        router.replace({ path: route.path, query });
    }
});
</script>

<template>
<div class="login-page-wrapper w-100 h-100 position-relative">
  <v-btn 
    prepend-icon="mdi-arrow-left" 
    variant="text" 
    color="medium-emphasis" 
    to="/" 
    class="position-absolute text-none font-weight-medium" 
    style="top: 24px; left: 24px; z-index: 10;"
  >Back to website</v-btn>

 <v-container class="fill-height d-flex align-center justify-center">
 <v-card 
 :color="theme.global.current.value.dark ? 'surface': 'transparent'" 
 width="100%"
 max-width="400"
 class="text-center pa-4"
 >
 <!--Header-->
 <div class="d-flex align-center justify-center mb-4">
 <h1 class="mb-0">{{ step === 1 ? 'Login' : 'Welcome back' }}</h1>
 </div>

 <!-- Error Message -->
 <v-alert v-if="errorMsg" type="error" variant="tonal" class="mb-4 text-caption" density="compact">{{ errorMsg }}</v-alert>
 <v-alert v-if="successMsg" type="success" variant="tonal" class="mb-4 text-caption" density="compact">{{ successMsg }}</v-alert>

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
 <span class="">{{ userData?.first_name[0] }}{{ userData?.last_name[0] }}</span>
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

  <!--Form Step 3: OTP-->
  <template v-if="step === 3">
    <div class="text-body-2 mb-4 text-medium-emphasis">
      We've sent a 6-digit verification code to your email and phone number.
    </div>
    
    <v-text-field 
      variant="outlined"
      density="comfortable"
      class="mb-4"
      placeholder="Enter 6-digit code"
      v-model="otp"
      :rules="[rules.required]"
      @keyup.enter="handleVerifyOtp"
      maxlength="6"
    ></v-text-field>
   
    <v-btn
      size="large"
      block 
      color="primary"
      class="text-none mb-6"
      :loading="loading"
      @click="handleVerifyOtp"
    >Verify & Login</v-btn>
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
 :loading="loadingGoogle"
 @click="googleLogin"
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
 </div>
</template>

<style scoped>
.extracted-style-1 {
 left: 16px;
}
</style>
