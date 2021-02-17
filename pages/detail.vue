<template>
  <v-container grid-list-xl fluid>
    <v-layout row wrap justify-center align-center>
      <v-flex xs12>
        <v-card>
          <v-toolbar color="#95e1d3" dark>
            <v-card-title class="headline">
              <v-icon x-large class="fa-2x"> mdi-folder-table-outline </v-icon>
              &nbsp; ตารางแสดงรายละเอียดผู้รับบริการ
            </v-card-title>
          </v-toolbar>

          <v-card-text>
            <v-row>
              <v-col cols="12" md="12" class="text-center">
                <v-card>
                  <v-card-title>
                    <v-text-field
                      v-if="loading"
                      v-model="search"
                      append-icon="mdi-magnify"
                      label="Search"
                      single-line
                      hide-details
                    ></v-text-field>
                  </v-card-title>
                  <v-data-table
                    width="20vh"
                    v-if="loading"
                    :headers="headers"
                    :items="opddata_detail"
                    :search="search"
                  ></v-data-table>
                  <v-skeleton-loader
                    v-if="skeleton"
                    class="mx-auto"
                    type=" heading@1 ,table-row-divider@6 "
                  ></v-skeleton-loader>
                </v-card>
              </v-col>
            </v-row>
          </v-card-text> </v-card
      ></v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import axios from 'axios'
export default {
  data: () => ({
    search: '',
    headers: [
      {
        text: 'รหัสสถานบริการ',

        // filterable: false,
        align: 'center',
        value: 'HOSPCODE_FULL',
      },
      { text: 'HN', value: 'PID', align: 'center' },
      { text: 'ลำดับการรับบริการ', value: 'visitno', align: 'center' },
      { text: 'วันที่รับบริการ', value: 'DATE_VISIT_THAI', align: 'center' },
      { text: 'รหัสสิทธิ', value: 'INSTYPE', align: 'center' },
      { text: 'สิทธิ', value: 'RIGHT_SERV', align: 'center' },
      { text: 'กลุ่มสิทธิ', value: 'RIGHT_GROUP', align: 'center' },
    ],

    opddata_detail: [],
    loading: false,
    skeleton: true,
  }),

  mounted() {
    this.fetch_opdetail()
  },
  methods: {
    async fetch_opdetail() {
      await axios
        .get(`${this.$axios.defaults.baseURL}opdata_detail.php`)
        .then((response) => {
          this.opddata_detail = response.data
          this.loading = true
          this.skeleton = false
        })
    },
  },
}
</script>
