<template>
    <div>

        <div v-for="(group_name, index) in group_names" v-bind:key="index" class="pb-5">
            <h2>{{group_name}}</h2>
            <div class="pl-10">
                <div v-for="(key_name, index) in Object.keys(rolesWithPermissions[ Object.keys(rolesWithPermissions)[selected_index] ])" v-bind:key="index">
                    <div v-if="rolesWithPermissions[ Object.keys(rolesWithPermissions)[selected_index] ][key_name]['group_by'] == group_name">
                         <input 
                            type="checkbox"
                            class="form-checkbox h-5 w-5 text-green-600"
                            v-bind:checked="rolesWithPermissions[ Object.keys(rolesWithPermissions)[selected_index] ][key_name]['value']"
                            @change="changedInputCheckbox($event, key_name)">
                        {{rolesWithPermissions[ Object.keys(rolesWithPermissions)[selected_index] ][key_name]['type']}}
                    </div>
                </div>
            </div>
        </div><!-- end group_name loop -->   

    </div>
</template>

<script>
export default {
    props: ['selected_index', 'rolesWithPermissions'],

    mounted(){
        console.log(this.group_names);
        console.log(this.rolesWithPermissions)
    },
    
    methods:{
        changedInputCheckbox(event, permission_name){

            this.$emit('set-permission-for-role', permission_name, event.target.checked)
        }
    },

    computed: {
        group_names: function(){
            
            let nameList = [];

           
            let permissionsNameForCurrentRole = this.rolesWithPermissions[ Object.keys(this.rolesWithPermissions)[this.selected_index] ];
            Object.keys(permissionsNameForCurrentRole).forEach( function(permission){
                
                let group_name = permissionsNameForCurrentRole[permission]['group_by'];

                if(!nameList.includes(group_name)){
                    nameList.push(group_name);
                }
                
            });
            const general_str = 'General';
            const role_str = 'Role';

            nameList.sort();
            nameList.splice(nameList.indexOf(role_str), 1)
            nameList.unshift(role_str);
            
            if(nameList.includes(general_str)){
                nameList.splice(nameList.indexOf(general_str), 1)
                nameList.unshift(general_str);
            }
            

            return nameList;
        }
    }

}


</script>