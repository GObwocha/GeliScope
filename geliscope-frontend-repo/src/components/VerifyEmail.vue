<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import axios from "axios";

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const status = ref("verifying");
const errorMsg = ref("");

onMounted(async () => {
    const token = route.query.token;
    if (!token) {
        status.value = "error";
        errorMsg.value = "Invalid verification link.";
        return;
    }

    try {
        const response = await axios.post("/api/v1/auth/verify-email", { token });
        
        // Auto login since token is returned
        authStore.user = response.data.user;
        authStore.token = response.data.token;
        localStorage.setItem("geliscope_user", JSON.stringify(authStore.user));
        localStorage.setItem("geliscope_token", authStore.token);
        
        status.value = "success";
        
        setTimeout(() => {
            router.push("/dashboard");
        }, 3000);
    } catch (error) {
        status.value = "error";
        errorMsg.value = error.response?.data?.message || "Verification failed. The link may have expired.";
    }
});
</script>

<template>
<v-container class="fill-height d-flex align-center justify-center">
    <v-card width="100%" max-width="400" class="text-center pa-8" elevation="2">
        <template v-if="status === `verifying`">
            <v-progress-circular indeterminate color="primary" size="64" class="mb-6"></v-progress-circular>
            <div class="text-h6 font-weight-medium">Verifying your email...</div>
            <div class="text-body-2 text-medium-emphasis mt-2">Please wait a moment.</div>
        </template>
        
        <template v-if="status === `success`">
            <v-icon icon="mdi-check-circle" color="success" size="64" class="mb-6"></v-icon>
            <div class="text-h6 font-weight-medium text-success">Email Verified!</div>
            <div class="text-body-2 text-medium-emphasis mt-2">You will be redirected to your dashboard shortly.</div>
            <v-btn color="primary" variant="flat" block class="mt-6" to="/dashboard">Go to Dashboard</v-btn>
        </template>
        
        <template v-if="status === `error`">
            <v-icon icon="mdi-alert-circle" color="error" size="64" class="mb-6"></v-icon>
            <div class="text-h6 font-weight-medium text-error">Verification Failed</div>
            <div class="text-body-2 text-medium-emphasis mt-2">{{ errorMsg }}</div>
            <v-btn color="primary" variant="flat" block class="mt-6" to="/login">Back to Login</v-btn>
        </template>
    </v-card>
</v-container>
</template>
