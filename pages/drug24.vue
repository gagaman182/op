<template>
  <v-container grid-list-xl fluid>
    <v-layout row wrap align-center>
      <v-flex xs12>
        <v-card>
          <v-toolbar color="#95e1d3" dark>
            <v-card-title class="headline">
              <v-icon x-large class="fa-2x"> mdi-pill </v-icon>
              &nbsp; ตารางแสดงข้อมูลยาเรื้อรัง
            </v-card-title>
          </v-toolbar>

          <v-card-text>
            <v-row>
              <v-col cols="12" md="12" class="text-center">
                <v-card>
                  <v-card-actions>
                    <v-spacer />
                    <v-btn v-if="loading" color="#1687a7" x-large dark>
                      <v-icon x-large> mdi-microsoft-excel </v-icon>
                      <export-excel
                        :data="opddata_detail_thainame"
                        name="ข้อมูลยาเรื้อรัง.xls"
                      >
                        Excel
                      </export-excel>
                    </v-btn>
                  </v-card-actions>

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
  name: 'all',
  data: () => ({
    search: '',
    headers: [
      { text: 'รหัสยา', value: 'drugcode', align: 'center' },
      { text: 'ชื่อยา', value: 'drugname', align: 'center' },
      { text: 'รหัส24หลัก', value: 'drugcode24', align: 'center' },
      { text: 'จำนวน', value: 'unit', align: 'center' },
    ],

    opddata_detail: [],
    opddata_detail_thainame: [],
    opddata_detail_date: [],
    loading: false,
    skeleton: true,
  }),
  computed: {
    monthstarts() {
      return this.$store.state.monthstart
    },
  },
  mounted() {
    this.fetch_opdetail_date()
  },
  methods: {
    async fetch_opdetail_date() {
      await axios
        .get(`${this.$axios.defaults.baseURL}opdata.php`)
        .then((response) => {
          this.opddata_detail_date = response.data

          this.monthstartstore = this.opddata_detail_date[0].MONTHSTART
          this.monthendstore = this.opddata_detail_date[0].MONTHEND

          axios
            .post(`${this.$axios.defaults.baseURL}drug24.php`, {
              monthstartstore: this.monthstartstore,
              monthendstore: this.monthendstore,
            })
            .then((response) => {
              this.opddata_detail = response.data

              this.loading = true
              this.skeleton = false
            })

          axios
            .post(`${this.$axios.defaults.baseURL}drug24_thainame.php`, {
              monthstartstore: this.monthstartstore,
              monthendstore: this.monthendstore,
            })
            .then((response) => {
              this.opddata_detail_thainame = response.data
            })
        })
    },
  },
}
</script>
<style scoped></style>
