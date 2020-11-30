<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                View Roles
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="flex flex-col md:flex-row">

                    <!-- first row -->
                    <div class="bg-white p-5 m-2 rounded md:w-60">
                        <div class="mb-2 text-2xl">
                            Roles
                        </div>
                        <ul class=" list-reset flex flex-col">
                            
                            <li v-for="(roleName, index) in Object.keys(rolesWithPermissions)" v-on:click="selectRoleIndex(index)" v-bind:key="index" v-bind:class="{'relative -mb-px block border p-4 border-grey cursor-pointer': true, 'bg-blue-500': selected_role_index == index}">{{roleName}}</li>

                        </ul>
                    </div>
                    <!-- end first row -->

                    <!-- second row -->
                    <div class="bg-white p-5 m-2 rounded flex-grow">
                         <div class="flex justify-between">
                            <p class="mb-2 text-2xl">{{Object.keys(rolesWithPermissions)[selected_role_index]}} Permissions</p>
                            <button
                                type="button"
                                class="border border-indigo-500 bg-indigo-500 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-indigo-600 focus:outline-none focus:shadow-outline"
                                v-on:click="updateRolesPermission"
                            >
                                Update {{Object.keys(rolesWithPermissions)[selected_role_index]}}
                            </button>
                        </div>
                        
                        <PermissionsFrom v-bind:selected_index="selected_role_index" v-bind:rolesWithPermissions="rolesWithPermissions" v-on:set-permission-for-role="changePermissionForRole"></PermissionsFrom>
                        
                    </div>
                    <!-- end second row -->
                </div>
                
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import PermissionsFrom from '@/Pages/Roles/PermissionsForm'
    import Button from '../../Jetstream/Button.vue'
    import Input from '../../Jetstream/Input.vue'

    export default {
        components: {
            AppLayout,
            PermissionsFrom,
            Button,
            Input  
        },

        props: ['rolesWithPermissions'],
        data(){
            return {
                'selected_role_index': 0,
            }
        },

        mounted(){
           
        },
    
        methods:{
            selectRoleIndex(index){
                this.selected_role_index = index
            },

            updateRolesPermission(){
                console.log(this.rolesWithPermissions[Object.keys(this.rolesWithPermissions)[this.selected_role_index]])
                
                let loader = this.$loading.show({
                  // Optional parameters
                  container: false,
                  canCancel: false,
                });

                axios({
                    // TODO: must make it dynamic
                    url: 'http://somitytracker.test/dashboard/update_role',
                    method: 'post',
                    data: {
                        'role' : this.rolesKeyFor(this.selected_role_index),
                        'permissions' : this.rolesWithPermissions[Object.keys(this.rolesWithPermissions)[this.selected_role_index]],
                    },
                    options: {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    },
                }).then((response) => {

                    console.log(response)
                    loader.hide();

                }, (err) => {

                    console.log(err)
                    loader.hide();

                })

            },

            changePermissionForRole(permission_name, is_checked){
                console.log(permission_name, is_checked)
                this.rolesWithPermissions[this.rolesKeyFor(this.selected_role_index)][permission_name] = is_checked;
            },

            rolesKeyFor(index){
                return Object.keys(this.rolesWithPermissions)[index];
            }

        }
    }
</script>
