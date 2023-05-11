<template>
  <div>
    <div v-if="items.length > 0">
      <leave-list-admin />
    </div>

    <div v-else>
      <b-alert show variant="info">
        İzin talebi bulunamadı.
      </b-alert>
    </div>
  </div>
</template>

<script>
    import LeaveListAdmin from './LeaveListAdmin';
    export default {
        name: 'LeaveAdminIndex',
        components:{
            leaveListAdmin : LeaveListAdmin
        },
        data(){
            return{
                items : 1
            }
        },
        mounted(){
            this.fetch();
        },
        methods: {
            async fetch(){
                const response = await this.$http.get('/api/leave-requests');

                this.items = response.data.data;
            }
        }
    }
</script>

<style scoped>

</style>
