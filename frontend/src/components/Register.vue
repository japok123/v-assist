<template>
    <v-card style="height:50vh" class="login">
        <v-card-title>
            Create Account
        </v-card-title>
        <v-card-text>
            <v-text-field v-model="form.email" outlined label="Email" type="email"/>
            <v-text-field v-model="form.firstname" outlined label="First name"/>
            <v-text-field v-model="form.lastname" outlined label="Last Name"/>
            <v-text-field v-model="form.password" outlined label="Password" type="password"/>
  
        </v-card-text>
        <v-card-actions class="d-flex justify-content-between align-items-center"> 
            <v-btn @click="submit" variant="tonal" color="success">
                register
            </v-btn>
            <router-link to="/">
                <v-btn color="primary" variant="text"> 
                   Log in 
                </v-btn>
            </router-link>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    data:()=>{
        return{
            form:{
                email:'',
                password:'',
                name:''
            }
        }
    },
    methods:{
        async submit(){
            await this.$axios.post('/register',this.form).then(response =>{
                if(response.data){
                    const token = response.data.token 
                    localStorage.setItem("token",token) 
                    localStorage.setItem("user",JSON.stringify(response.data)) 
                    this.$router.push({ name: 'Home' });
                }
                const token = document.cookie.split('; ').find(row => row.startsWith('authToken='));
                this.$swal.fire('Success','','success')
            }).catch(err=>{
                console.log(err)
                this.$swal.fire('error','','error')
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