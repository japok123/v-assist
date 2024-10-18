<template>
  <v-card class="w-50" elevation="5">
    <v-card-header class="d-flex justify-content-between align-items-center p-3">
        <h4>
            TODO LIST
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
                            :disabled="[4,3].includes(item.status)"
                            @click="view(item);dialog.action = 'edit' "
                            ><v-icon class="btn-action" small size="18">mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn
                            variant="text"
                            icon
                            color="orange"
                            :disabled="[4,3].includes(item.status)"
                            :loading="!dataTable.loaded"
                            @click="deleteTodo(item); "
                            ><v-icon class="btn-action" small size="18">mdi-delete</v-icon>
                        </v-btn>
          
                      </template>
                      <template v-slot:[`item.status`]="{ item }" >
                        <v-chip :color="statuses.find(e=>e.value == item.status).color" variant="flat" size="small">
                            {{statuses.find(e=>e.value == item.status).text}}
                        </v-chip>
                        
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
                    {{ dialog.action.toUpperCase() }} TODO 
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
                    <v-col lg="6">
                        <v-text-field v-model="form.todo" outlined label="Todo" :disabled="dialog.action == 'view'"/>
                    </v-col>
                    <v-col lg="6">
                        <v-autocomplete
                            :items="statuses"
                            v-model="form.status"
                            item-value="value"
                            item-title="text"
                            label="Status"
                            :disabled="dialog.action == 'view'"
                        >

                        </v-autocomplete>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col lg="12">
                        <v-textarea v-model="form.description" outlined label="Description"  :disabled="dialog.action == 'view'"></v-textarea>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-btn @click="modifyTodo" color="success" v-if="dialog.action != 'view'">
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
                { title:'Todo',value:'todo' },
                { title:'Status',value:'status' },
                { title:'name',value:'name' },
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
            todo:'',
            description:'',
            status:1
        },

        dialog:{
            action:'',
            selected:{},
        },
        statuses:[
            { text:'Pending',color:'secondary',bgColor:'bg-secondary', value:1},
            { text:'Doing',color:'primary',bgColor:'bg-primary', value:2},
            { text:'Completed',color:'success',bgColor:'bg-success', value:3},
            { text:'Cancel',color:'red',bgColor:'bg-red', value:4},
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
        
        deleteTodo(item){
            this.$swal.fire({
                title:'Are you Sure you want to delete?',
                text:'-',
                showCancelButton:true,
                icon:'warning'
            }).then(action=>{
                if(action.isConfirmed){
                    this.$axios.get('/deleteTodo/' + item.id).then(response=>{
                        this.$swal.fire('Success!','','success')
                        this.list()
                    }).catch(err=>{
                        console.log(err)
                        this.$swal.fire('Error!','','error')
                    })
                }
            })
        },
        
        modifyTodo(){
            console.log(this.form)
            this.$axios.post('/addTodo',{  
                ...this.form
            }).then(response=>{
                this.dialog.value = false
                this.$swal.fire('Success!','','success')
                this.list()
                this.resetField()
            }).catch(err=>{
                console.log(err)
                this.$swal.fire('Error!','','error')
            })
        },
        list(){
            console.log(localStorage.getItem('user'))
            this.dataTable.loaded = false
            this.$axios.get('/todo').then(response=>{
                this.dataTable.items = response.data
                this.dataTable.loaded = true
            }).catch(err=>{
                console.log(err)
                this.$swal.fire('Error!','','error')
            })
        },
        resetField(){
            this.form = {
                todo:'',
                description:'',
                status:1
            }
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
