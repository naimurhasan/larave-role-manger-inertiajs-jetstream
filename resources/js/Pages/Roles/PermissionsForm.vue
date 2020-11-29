<template>
    <div>
                       
        <div v-for="(permission_boolean, permission_name, index) in rolesWithPermissions[ Object.keys(rolesWithPermissions)[selected_index] ]" v-bind:Key="index">
            <label vclass="inline-flex items-center mt-3">
                <input type="checkbox" class="form-checkbox h-5 w-5 text-green-600" v-bind:checked="permission_boolean" @change="changedInputCheckbox($event, permission_name)"><span class="ml-2 text-gray-700">
                    {{permission_name}}
                </span>
            </label>
        </div>

    </div>
</template>

<script>
export default {
    props: ['selected_index', 'rolesWithPermissions'],

    mounted(){
        
    },
    
    methods:{
        changedInputCheckbox(event, permission_name){
            this.$emit('set-permission-for-role', permission_name, event.target.checked)
        }
    },

}
</script>