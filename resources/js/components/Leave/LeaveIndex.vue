<template>
  <div>
    <div v-if="items.length > 0">
      <leave-list />
    </div>

    <div v-else>
      <b-alert show variant="info">
        İzin talebiniz bulunmamaktadır,
        <router-link :to="{ name: 'create'}">
          talepte bulunmak için tıklayın
        </router-link>
      </b-alert>
    </div>
  </div>
</template>

<script>
    import LeaveList from './LeaveList';

    export default {
        name: 'LeaveIndex',
        components:{
            'leaveList': LeaveList
        },
        data(){
            return {
                items : 1
            }
        },
        mounted(){
            this.fetch();
        },
        methods: {
           async fetch(){
               const response = await this.$http.get('/api/leaves');

               this.items = response.data.data;
            }
        }
    }
</script>

<style scoped>

</style>
