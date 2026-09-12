<script setup>
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const errorMsg = ref('');

onMounted(async () => {
    const token = route.query.token;

    if (token) {
        try {
            await authStore.fetchUser(token);
            router.push('/dashboard');
        } catch (error) {
            console.error('Failed to authenticate with Google', error);
            errorMsg.value = 'Authentication failed. Please try again.';
            setTimeout(() => {
                router.push('/login');
            }, 2000);
        }
    } else {
        router.push('/login');
    }
});
</script>

<template>
    <v-container class="fill-height d-flex align-center justify-center">
        <div class="text-center">
            <v-progress-circular indeterminate color="primary" size="64" class="mb-4" v-if="!errorMsg"></v-progress-circular>
            <p v-if="!errorMsg" class="text-h6">Authenticating...</p>
            <v-alert v-if="errorMsg" type="error" variant="tonal">{{ errorMsg }}</v-alert>
        </div>
    </v-container>
</template>