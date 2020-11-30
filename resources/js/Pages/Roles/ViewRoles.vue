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
                        <div class="flex flex-row justify-between items-center">
                            <div class="mb-2 text-2xl">
                                Roles
                            </div>
                            <button type="button"
                                class="border border-indigo-500 bg-indigo-500 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-indigo-600 focus:outline-none focus:shadow-outline"
                                v-on:click="newRole">
                                New
                            </button>
                        </div>

                        <ul class=" list-reset flex flex-col">

                            <li v-for="(roleName, index) in Object.keys(rolesWithPermissions)"
                                v-on:click="selectRoleIndex(index)" v-bind:key="index"
                                v-bind:class="{'relative -mb-px block border p-4 border-grey cursor-pointer': true, 'bg-blue-500': selected_role_index == index}">
                                {{roleName}}</li>

                        </ul>
                    </div>
                    <!-- end first row -->

                    <!-- second row -->
                    <div class="bg-white p-5 m-2 rounded flex-grow">
                        <div class="flex justify-between">
                            <p class="mb-2 text-2xl">{{Object.keys(rolesWithPermissions)[selected_role_index]}}
                                Permissions</p>
                            <button type="button"
                                class="border border-indigo-500 bg-indigo-500 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-indigo-600 focus:outline-none focus:shadow-outline"
                                v-on:click="updateRolesPermission">
                                Update {{Object.keys(rolesWithPermissions)[selected_role_index]}}
                            </button>
                        </div>

                        <PermissionsFrom v-bind:selected_index="selected_role_index"
                            v-bind:rolesWithPermissions="rolesWithPermissions"
                            v-on:set-permission-for-role="changePermissionForRole"></PermissionsFrom>
                        <div class="text-right">
                            <button
                                type="button"
                                class="border border-red-500 bg-red-500 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-red-600 focus:outline-none focus:shadow-outline"
                                v-on:click="deleteRole"
                            >
                                Delete {{Object.keys(rolesWithPermissions)[selected_role_index]}} Role
                            </button>
                        </div>
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
        data() {
            return {
                'selected_role_index': 0,
            }
        },

        mounted() {

        },

        methods: {
            selectRoleIndex(index) {
                this.selected_role_index = index
            },

            updateRolesPermission() {

                let loader = this.$loading.show({
                    // Optional parameters
                    canCancel: false,
                });

                axios({
                    // TODO: must make it dynamic
                    url: route('update_role').template,
                    method: 'post',
                    data: {
                        'role': this.rolesKeyFor(this.selected_role_index),
                        'permissions': this.rolesWithPermissions[Object.keys(this.rolesWithPermissions)[this
                            .selected_role_index]],
                    },
                    options: {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    },
                }).then((response) => {

                    loader.hide();
                    this.$toast.open('Update Success!');

                }, (err) => {

                    console.log(err)
                    loader.hide();
                    this.$toast.open({
                        message: 'Something went wrong!',
                        type: 'error',
                    });

                })

            },

            changePermissionForRole(permission_name, is_checked) {
                this.rolesWithPermissions[this.rolesKeyFor(this.selected_role_index)][permission_name] = is_checked;
            },

            newRole() {

                this.$swal({
                    title: 'Enter Name of New Role',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'on',
                        required: 'true',
                    },
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value === '') {
                                resolve('Can\'t be empty.')

                            } else if (Object.keys(this.rolesWithPermissions).includes(value)) {
                                resolve('Role already exist.')
                            } else {
                                resolve()
                            }
                        })
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Create',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {

                    console.log('result');
                    console.log(result);

                    if (result.isConfirmed) {

                        let loader = this.$loading.show({
                            // Optional parameters
                            canCancel: false,
                        });

                        axios({
                            // TODO: must make it dynamic
                            url: route('create_role').template,
                            method: 'post',
                            data: {
                                'name': result.value,
                            },
                            options: {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            },
                        }).then((response) => {

                            loader.hide();
                            this.$toast.open('Create Success!');
                            
                            console.log(response.data)

                            // add the role in vue
                            this.$set(this.rolesWithPermissions, result.value, response.data)

                        }, (err) => {
                            
                            console.log('Error: [ViewRole/NewRole/Axios]')
                            loader.hide();
                            this.$toast.open({
                                message: 'Something went wrong!',
                                type: 'error',
                            });

                        })
                    }
                })

            },

            deleteRole(){
                this.$swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                    if (result.isConfirmed) {
                         
                        let loader = this.$loading.show({
                            // Optional parameters
                            canCancel: false,
                        });

                        axios({
                            // TODO: must make it dynamic
                            url: route('delete_role').template,
                            method: 'delete',
                            data: {
                                'name': this.rolesKeyFor(this.selected_role_index),
                            },
                            options: {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            },
                        }).then((response) => {

                            loader.hide();
                            this.$toast.open('Delete Success!');
                            
                            console.log(response.data)

                            // delete the role in vue
                            this.$delete(this.rolesWithPermissions, this.rolesKeyFor(this.selected_role_index))
                            this.selected_role_index = 0;


                        }, (err) => {
                            
                            console.log('Error: [ViewRole/NewRole/Axios]')
                            loader.hide();
                            this.$toast.open({
                                message: 'Something went wrong!',
                                type: 'error',
                            });

                        })

                    }
                })
            },

            rolesKeyFor(index) {
                return Object.keys(this.rolesWithPermissions)[index];
            }

        }
    }

</script>
