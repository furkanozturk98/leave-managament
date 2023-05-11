<template>
  <div>
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
      <template v-slot:cell(leave_type_id)="row">
        {{ getLeaveTypeById(row.item.leave_type_id) }}
      </template>

      <template v-slot:cell(approve)="row">
        <b-button
          v-if="row.item.status === 1"
          variant="outline-primary"
          @click="approve(row.item.id)"
        >
          <b-icon-check-circle />
        </b-button>
      </template>

      <template v-slot:cell(reject)="row">
        <b-button
          v-if="row.item.status === 1"
          variant="outline-danger"
          @click="reject(row.item.id)"
        >
          <b-icon-x-circle />
        </b-button>
      </template>

      <template v-slot:cell(status)="row">
        <b-badge :variant="variants[row.item.status]">
          {{ getStatusNameById(row.item.status) }}
          <b-badge />
        </b-badge>
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
        name : 'LeaveListAdmin',
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
                        key: 'approve',
                        label: '',
                    },
                    {
                        key: 'reject',
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
            async fetch(ctx) {
                this.busy = true;

                const sortDir = this.sortDesc ? 'DESC' : '';

                const response = await this.$http.get(`/api/leave-requests?page=${ctx.currentPage}&per_page=${ctx.perPage}&sort=${this.sortBy}&dir=${sortDir}`);

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
            async approve(id) {
                try {
                    const value = await this.$bvModal.msgBoxConfirm('Bu izin talebini onaylamak istediğinize emin misiniz?', {
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
                        await this.$http.put('/api/leaves/' + id + '/approve');
                        this.$refs.table.refresh();
                    }

                } catch (e) {
                    console.log(e);
                }
            },

            async reject(id) {
                try {
                    const value = await this.$bvModal.msgBoxConfirm('Bu izin talebini reddetmek istediğinize emin misiniz?', {
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
                        await this.$http.put('/api/leaves/' + id + '/reject');
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
        },
    }
</script>
