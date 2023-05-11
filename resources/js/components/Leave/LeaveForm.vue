<template>
  <div>
    <b-form @submit.prevent="submit">
      <b-form-group
        label="İzin Türü:"
        label-for="leave_type_id"
        :invalid-feedback="form.errors.first('leave_type_id')"
      >
        <b-form-select
          id="leave_type_id"
          v-model="form.leave_type_id"
          :options="leaveTypeOptions"
          :state="form.errors.has('leave_type_id') ? false : null"
        />
      </b-form-group>

      <b-form-group
        label="Açıklama:"
        label-for="description"
        :invalid-feedback="form.errors.first('description')"
      >
        <b-form-textarea
          id="description"
          v-model="form.description"
          max-rows="6"
          placeholder="Açıklamayı girin"
          rows="3"
          :state="form.errors.has('description') ? false : null"
        />
      </b-form-group>

      <b-form-group
        label="Başlangıç Tarihi:"
        label-for="start_date"
        :invalid-feedback="form.errors.first('start_date')"
      >
        <b-form-datepicker
          id="start_date"
          v-model="form.start_date"
          style="width:300px"
          class="mb-2"
          :state="form.errors.has('start_date') ? false : null"
          label-no-date-selected="Tarih seçilmedi"
        />
      </b-form-group>

      <b-form-group
        label="Bitiş Tarihi:"
        label-for="end_date"
        :invalid-feedback="form.errors.first('end_date')"
      >
        <b-form-datepicker
          id="end_date"
          v-model="form.end_date"
          style="width:300px"
          class="mb-2"
          :state="form.errors.has('end_date') ? false : null"
          label-no-date-selected="Tarih seçilmedi"
        />
      </b-form-group>

      <div class="float-right">
        <b-button @click="back" type="button" variant="secondary">
          Vazgeç
        </b-button>
        <b-button type="submit" variant="primary">
          Kaydet
        </b-button>
      </div>
    </b-form>
  </div>
</template>

<script>
    import Form from 'form-backend-validation';

    export default {
        name: 'LeaveForm',
        data() {
            return {
                form: new Form({
                        user_id: null,
                        leave_type_id: null,
                        description: '',
                        start_date: null,
                        end_date: null,
                        status: 0
                    },
                    {
                        resetOnSuccess: false,
                    }
                ),
                leaveTypeOptions: [],
                modelId: null
            }
        },

        mounted() {
            this.modelId = this.$route.params.id;
            if (this.modelId) {
                this.fetch();
            }

            this.getLeaveTypeOptions();
        },

        methods: {
            async fetch() {
                const response = await this.$http.get('/api/leaves/' + this.modelId);

                this.form.populate(response.data.data);
            },

            async getLeaveTypeOptions() {
                const response = await this.$http.get('/api/leave-types');

                // es6
                this.leaveTypeOptions = [
                    { value: null, text: '(Izin turu secin)' },

                    ...response.data.data.map(item => ({
                        value: item.id,
                        text: item.name
                    }))
                ];
            },

            async store() {
                try {
                    await this.form.post('/api/leaves');
                    this.$router.push({ name: 'leave.list' });
                } catch (e) {
                    console.log(e);
                }
            },

            async update() {
                try {
                    await this.form.put('/api/leaves/' + this.modelId);
                    this.$router.push({ name: 'leave.list' });
                } catch (e) {
                    console.log(e);
                }
            },

            async submit() {
                this.modelId
                    ? await this.update()
                    : await this.store();
            },

            back() {
                this.$router.push({ name: 'leave.list' });
            }

        }
    }
</script>

<style scoped>

</style>
