<template>
  <div>
    <div class="float-right" style="margin-bottom:15px;">
      <b-button @click="create" variant="primary">
        Yeni İzin
      </b-button>
    </div>
    <br>
    <b-table
      ref="table"
      :busy="busy"
      :current-page="currentPage"
      :fields="fields"
      :items="fetch"
      :per-page="perPage"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      hover
      show-empty
      sort-icon-left
      striped
    >
      <template v-slot:cell(user_name)="row">
        <router-link :to="{ name: 'edit', params: { id: row.item.id }}" v-if="!row.item.status">
          {{ row.item.user_name }}
        </router-link>
        <div v-if="row.item.status">
          {{ row.item.user_name }}
        </div>
      </template>

      <template v-slot:cell(leave_type_id)="row">
        {{ getLeaveTypeById(row.item.leave_type_id) }}
      </template>

      <template v-slot:cell(status)="row">
        <b-badge :variant="variants[row.item.status]">
          {{ getStatusNameById(row.item.status) }}
        </b-badge>
      </template>

      <template v-slot:cell(send)="row">
        <b-button @click="send(row.item.id)" v-if="!row.item.status" variant="outline-primary">
          <b-icon-check-circle />
        </b-button>
      </template>

      <template v-slot:cell(delete)="row">
        <b-button @click="destroy(row.item.id)" v-if="!row.item.status" variant="outline-danger">
          <b-icon-trash />
        </b-button>
      </template>
    </b-table>

    <b-pagination
      v-model="currentPage"
      :per-page="perPage"
      :total-rows="totalRows"
      align="right"
    />
  </div>
</template>

<script>

    export default {
        name : 'LeaveList',
        data() {
            return {
                fields: [
                    {
                        key: 'user_name',
                        label: 'Kullanıcı Adı',
                    },
                    {
                        key: 'leave_type_id',
                        label: 'İzin Türü',
                    },
                    {
                        key: 'description',
                        label: 'Açıklama',
                    },
                    {
                        key: 'start_date',
                        label: 'Başlangıç Tarihi',
                        sortable: true,
                    },
                    {
                        key: 'end_date',
                        label: 'Bitiş Tarihi',
                        sortable: true,
                    },
                    {
                        key: 'status',
                        label: 'Durum',
                        sortable: true
                    },
                    {
                        key: 'approved_at',
                        label: 'Onaylanma Tarihi',
                        sortable: true,
                    },
                    {
                        key: 'created_at',
                        label: 'Oluşturulma Tarihi',
                        sortable: true,
                    },
                    {
                        key: 'send',
                        label: '',
                    },
                    {
                        key: 'delete',
                        label: '',
                    }
                ],
                items: [],
                leaveTypes: [],
                show: false,
                variants : {
                    0: 'secondary',
                    1: 'warning',
                    2: 'success',
                    3: 'danger'
                },
                busy: false,

                sortBy: 'status',
                sortDesc: false,
                totalRows: 0,
                currentPage: 1,
                perPage: 10,
            }
        },
        mounted() {
            this.getLeaveTypes();
        },
        methods: {
            async fetch(ctx){
                this.busy = true;

                const sortDir = this.sortDesc ? 'DESC' : '';

                const response = await this.$http.get(`/api/leaves?page=${ctx.currentPage}&per_page=${ctx.perPage}&sort=${this.sortBy}&dir=${sortDir}`);

                this.items = response.data.data;

                this.totalRows = response.data.meta.total;

                this.busy = false;

                return this.items;
            },
            async getLeaveTypes(){
                const response = await this.$http.get('/api/leave-types');
                let data = response.data.data;

                Object.keys(data).forEach(key => {
                    this.leaveTypes.push(data[key].name);
                });

            },
            async destroy(id) {
                try {
                    const value = await this.$bvModal.msgBoxConfirm('Bu izin taslağını silmek istediğinize emin misiniz?', {
                        title: 'Lütfen Onaylayın',
                        size: 'sm',
                        buttonSize: 'sm',
                        okVariant: 'danger',
                        okTitle: 'Evet',
                        cancelTitle: 'Iptal',
                        footerClass: 'p-2',
                        hideHeaderClose: false,
                        centered: true
                    });
                    if (value) {
                        await this.$http.delete('/api/leaves/' + id);
                        this.$refs.table.refresh();
                    }

                } catch (e) {
                    console.log(e);
                }

            },
            async send(id) {
                try {
                    const value = await this.$bvModal.msgBoxConfirm('Bu izin taslağını göndermek istediğinize emin misiniz?', {
                        title: 'Lütfen Onaylayın',
                        size: 'sm',
                        buttonSize: 'sm',
                        okVariant: 'primary',
                        okTitle: 'Evet',
                        cancelTitle: 'Iptal',
                        footerClass: 'p-2',
                        hideHeaderClose: false,
                        centered: true
                    });
                    if (value) {
                        await this.$http.put('/api/leaves/' + id + '/send');
                        this.$refs.table.refresh();
                    }

                } catch (e) {
                    console.log(e);
                }
            },
            getStatusNameById(id) {
                const status = {
                    0: 'Taslak',
                    1: 'Onay Bekliyor',
                    2: 'Onaylandı',
                    3: 'Reddedildi'
                };
                return status[id];
            },
            getLeaveTypeById(id){
              return this.leaveTypes[id-1];
            },
            create() {
                this.$router.push({ name: 'create' });
            }
        },
    }
</script>
