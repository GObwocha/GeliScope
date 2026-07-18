<script setup>
    import { ref } from 'vue';
    import { useTheme } from 'vuetify';
    const theme = useTheme()
    const email = ref(null)
    const password = ref(null)
    const showPassword = ref(false)
    //Rules
    const rules = {
    required: value => !!value || 'Required.',//if statements 
    min: v => v.length >= 8 || 'Min 8 characters', //if statements
    passwordMatch: () => password == confirmPassword || 'Passwords must match' //function to check passwords are matching
  } 

</script>

<template>
    <v-container class="fill-height d-flex align-center justify-center">
        <v-card 
        :color="theme.global.current.value.dark ? 'cardBg': 'transparent'" 
        width="100%"
        max-width="400"
        class="text-center pa-4"
        >
            <!--Header-->
            
            <h1>Login</h1>
            

            <!--Form-->
            <!-- Email -->
            <v-text-field 
            variant="outlined"
            density="comfortable"
            class="mb-4"
            placeholder="Email address"
            hide-details
            v-model="email"
            >
            </v-text-field>

            <!--Submit button-->
            <v-btn
            size="large"
            block 
            color="primary"
            class="text-none mb-6"
            >Submit</v-btn>

            <!-- Divider -->
             <v-divider>or continue with</v-divider>

             <!-- Google button -->
            <v-btn
            prepend-icon="mdi-google"
            block
            color="black"
            size="large"
            variant="flat"
            class="text-none mb-8"
            >
                Google
            </v-btn>
            
            <!--Form footer-->
            <p class="text-caption text-medium-emphasis mb-4">
                By clicking continue, you agree to our 
                <a class="text-decoration-none text-high-emphasis font-weight-medium">Terms of Services</a>
                and 
                <a class="text-decoration-none text-high-emphasis font-weight-medium">Privacy Policy</a>
            </p>

            <div class="text-body-2">
                Don't have an account? 
                <router-link to="/register" class="text-primary font-weight-bold text-decoration-none">Sign up</router-link>
            </div>

        </v-card>       
    </v-container>
</template>