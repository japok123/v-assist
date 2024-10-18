<template>
   <v-card>
    <v-layout>
      <v-navigation-drawer floating permanent class="bg-primary">
        <v-list density="compact" nav >
            <v-list-item
            prepend-icon="mdi-account"
            :title="user.firstname.toUpperCase() + ' ' + user.lastname.toUpperCase()"
            variant="outlined"
            value="me"
          ></v-list-item>
          <v-list-item
            prepend-icon="mdi-view-dashboard"
            title="Todo List"
            value="home"
            @click="action('todo-list')"
          ></v-list-item>
          <v-list-item
            v-if="user.type == 2"
            prepend-icon="mdi-list-box-outline"
            title="Users"
            value="Users"
            @click="action('users')"
          ></v-list-item>
          <v-list-item
            prepend-icon="mdi-logout"
            title="Log out"
            value="logout"
            @click="action(3)"
          ></v-list-item>

        </v-list>
      </v-navigation-drawer>
      <v-main style="height: 100vh" class="d-flex justify-content-center align-items-center p-0">   
        <router-view></router-view>
      </v-main>
    </v-layout>
  </v-card>
</template>

<script>

import SharedFunctions from '@/sharedFunction/SharedFunctions.vue';
export default {
     mixins:[SharedFunctions],
    data:()=>{
        return{
            navigations:[
                { prependIcon:'mdi-view-dashboard',title:'Todo List', routeName:'todo-list',value:1},
                { prependIcon:'mdi-list-box-outline',title:'Users', routeName:'users',value:2},
                { prependIcon:'mdi-logout',title:'Log out', routeName:'Login',value:3},
            ],
            user:{}
        }
    },
    beforeMount(){
         this.user = JSON.parse(localStorage.getItem('user')).user
         console.log(this.user)
    },
    methods:{
        async action(item){
            if(item == 3){
                this.logout()
            }else{
                this.$router.push({ name: item });
            }
        },
        async logout() {
            await this.$axios.get('/logout')
            .then(response => {

                localStorage.removeItem('token')
                this.$router.push({ name: 'Login' });
            })
            .catch(err => {
                console.error('Error logging out:', err);
            });
        }

    }
}
</script>

<style lang="scss" scoped>
    .background{
        background: linear-gradient(to bottom, #feb47b, #ff7e5f);

    }
</style>