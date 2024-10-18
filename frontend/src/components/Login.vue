<template>
    <v-card style="height:30vh" class="login">
        <v-card-title>
            Log in 
        </v-card-title>
        <v-card-text>
            <v-text-field v-model="form.email" outlined label="Email" type="email">

            </v-text-field>
            <v-text-field v-model="form.password" outlined label="Password" type="password">

            </v-text-field>
        </v-card-text>
        <v-card-actions class="d-flex justify-content-between align-items-center">
            <v-btn @click="submit" variant="tonal" color="primary">
                Log in
            </v-btn>
            <router-link to="/register">
                <v-btn color="primary" variant="text">
                    Create Account
                </v-btn>
            </router-link>
        </v-card-actions>
    </v-card>
</template>

<script>
import SharedFunctions from '@/sharedFunction/SharedFunctions.vue';
export default {
     mixins:[SharedFunctions],
    data:()=>{
        return{
            form:{
                email:'',
                password:''
            },
            error:{
                value:false,
                text:''

            }
        }
    },
    mounted(){
        // this.$axios.get('/sanctum/csrf-cookie').then(response => {
        //     console.log(response)
        // });
    },
    methods:{
        async submit(){

            let checker = [
                this.verifyEmail(this.form.email),
                !!this.form.email,
                !!this.form.password
            ]

            if([false].includes(checker)){
                return false
            }

            await this.$axios.post('/login',this.form).then(response =>{
                if(response.data){
                    const token = response.data.token 
                    localStorage.setItem("token",token) 
                    localStorage.setItem("user",JSON.stringify(response.data)) 
                    this.$router.push({ name: 'Home' });
                }
                // const token = document.cookie.split(';').find(row => row.startsWith('authToken='));
            }).catch(err=>{
                console.log(err)
                this.$swal.fire('error',err.message,'error')
            })
        }
    }
}
</script>

<style lang="scss" scoped>
    .background{
        background: linear-gradient(to bottom, #feb47b, #ff7e5f);
    }
    .login{
        width: 500px !important;
    }
</style>