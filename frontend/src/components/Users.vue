<template>
    <v-card class="w-50">
      <v-card-header class="d-flex justify-content-between align-items-center p-3">
          <h4>
              USER LIST
          </h4>
          <span>
              <v-btn variant="tonal" color="success" @click="()=>{
                  dialog.value = true;
                  dialog.action = 'add'}">
                  <v-icon>mdi-plus</v-icon> Add
              </v-btn>
          </span>
      </v-card-header>
      <v-card-text>
          <v-row>
              <v-col lg="12">
                  <v-data-table
                      :headers="dataTable.headers"
                      :items="dataTable.items"
                      :server-items-length="dataTable.total_items"
                      :options.sync="filter.options"
                      :loading="!dataTable.loaded"
                      disable-sort
                      dense
                  >
                  <template v-slot:[`item.actions`]="{ item }" >
                          <v-btn
                              variant="text"
                              icon
                              color="orange"
                              :loading="!dataTable.loaded"
                              @click="view(item); dialog.action = 'view'"
                              ><v-icon class="btn-action" small size="18">mdi-eye</v-icon>
                          </v-btn>
                          <v-btn
                              variant="text"
                              icon
                              color="orange"
                              :loading="!dataTable.loaded"
                              @click="view(item);dialog.action = 'edit' "
                              ><v-icon class="btn-action" small size="18">mdi-pencil</v-icon>
                          </v-btn>
                          <v-btn
                              variant="text"
                              icon
                              color="orange"
                              :loading="!dataTable.loaded"
                              @click="deleteUser(item); "
                              ><v-icon class="btn-action" small size="18">mdi-delete</v-icon>
                          </v-btn>
            
                        </template>
                        <template v-slot:[`item.type`]="{ item }" >
                            <span>{{ item.type == 1 ? 'User' : 'Admin' }}</span>
                          
                        </template>
                        <template v-slot:[`item.created_at`]="{ item }" >
                            {{ formatDate(item.created_at)}}
                        </template>
                        <template v-slot:[`item.updated_at`]="{ item }" >
                                {{ formatDate(item.updated_at)}}
                        </template>
                  </v-data-table>
              </v-col>
          </v-row>
      </v-card-text>
      <v-dialog v-model="dialog.value" width="600">
          <v-card>
  
              <v-card-header class="d-flex justify-content-between align-items-center p-3">
                  <span>
                      {{ dialog.action.toUpperCase() }} USER 
                  </span>
                  <span>
                      <v-btn variant="text" @click="()=>{
                          dialog.value = false;
                          dialog.action = ''}">
                          <v-icon>mdi-close-circle</v-icon>
                      </v-btn>
                  </span>
              </v-card-header>
              <v-card-text>
                <v-row>
                    <v-col lg="12">
                        <v-autocomplete
                            :items="types"
                            v-model="form.type"
                            item-value="value"
                            item-title="text"
                            label="Type"
                            :disabled="dialog.action == 'view'"
                        >

                        </v-autocomplete>
                    </v-col>
                </v-row>
                  <v-row>
                      <v-col lg="12">
                          <v-text-field v-model="form.firstname" outlined label="First name" :disabled="dialog.action == 'view'"/>
                      </v-col>  
                  </v-row>
                  <v-row>
                    <v-col lg="12">
                        <v-text-field v-model="form.lastname" outlined label="Last name" :disabled="dialog.action == 'view'"/>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col lg="12">
                        <v-text-field v-model="form.email" outlined label="Email" :disabled="dialog.action == 'view'"/>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col>
                        <v-text-field v-model="form.password" outlined label="Password" type="password" :disabled="dialog.action == 'view'"/>
                    </v-col>
                  </v-row>
              </v-card-text>
              <v-card-actions>
                  <v-btn @click="modifyUser" color="success" v-if="dialog.action != 'view'">
                      Save
                  </v-btn>
              </v-card-actions>
          </v-card>
      </v-dialog>
    </v-card>
  </template>
  
  <script>
import SharedFunctions from '@/sharedFunction/SharedFunctions.vue';

  export default {
    mixins:[SharedFunctions],
    data() {
      return {
          dataTable:{
                items:[],
                headers:[
                    { title:'Firstname',value:'firstname' },
                    { title:'Lastname',value:'lastname' },
                    { title:'Type',value:'type' },
                    { title:'Created',value:'created_at' },
                    { title:'Updated',value:'updated_at' },
                    { title:'Actions',value:'actions' },
                ],
                options:{},
                loaded:true
            },
            filter:{
                options:{}
            },
    
            form:{
                firstname:'',
                lastname:'',
                email:'',
                type:1
            },
    
            dialog:{
                action:'',
                selected:{},
            },
            types:[
                    { text:'User', value:1},
                    { text:'Admin', value:2},
            ]
      };
    },
    mounted(){
      this.list();
    },
  
    methods: {
          view(item){
              this.form = item
              this.dialog.value = true
          },
          
          deleteUser(item){
              this.$swal.fire({
                  title:'Are you Sure you want to delete?',
                  text:'-',
                  showCancelButton:true,
                  icon:'warning'
              }).then(action=>{
                  if(action.isConfirmed){
                      this.$axios.get('/deleteUser/' + item.id).then(response=>{
                          this.$swal.fire('Success!','','success')
                          this.list()
                      }).catch(err=>{
                          console.log(err)
                          this.$swal.fire('Error!','','error')
                      })
                  }
              })
          },
          
          async modifyUser(){

            await this.$axios.post('/register',this.form).then(response =>{
                this.$swal.fire('Success','','success')
                this.dialog.value = false
                this.list();
            }).catch(err=>{
                console.log(err)
                this.$swal.fire('error','','error')
            })
            
          },
          list(){
              this.dataTable.loaded = false
              this.$axios.get('/users').then(response=>{
                  this.dataTable.items = response.data
                  this.dataTable.loaded = true
              }).catch(err=>{
                  console.log(err)
                  this.$swal.fire('Error!','','error')
              })
          },
          resetField(){
              this.form = {
                firstname:'',
                lastname:'',
                email:'',
                type:1
              }
          },
          async register(){
            await this.$axios.post('/register',this.form).then(response =>{
                this.$swal.fire('Success','','success')
            }).catch(err=>{
                console.log(err)
                this.$swal.fire('error','','error')
            })
        }
    },
    watch:{   
      'filter':{
          handler(val){
              this.getTodo()
          },
          deep:true
      }
    }
  };
  </script>
  
  <style lang="scss" scoped>
  </style>
  