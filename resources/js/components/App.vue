<template>
  <div>
    <router-view />
  </div>
</template>

<script>
    export default {
        name: 'App',
        methods :{
            myLeaves(){
                this.$router.push({ name : 'leave.list' });
            },
            employeeLeaveRequests(){
                this.$router.push({ name : 'leave.list.manager' });
            }
        }

    }
</script>

<style scoped>

</style>
