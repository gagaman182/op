<template>
  <v-container grid-list-xl fluid>
    <v-layout row wrap align-center>
      <v-flex xs12>
        <v-card>
          <v-toolbar color="#95e1d3" dark>
            <v-card-title class="headline">
              <v-icon x-large class="fa-2x"> mdi-cash-multiple </v-icon>
              &nbsp; ตารางแสดงข้อมูลตามกลุ่มค่าใช้จ่าย
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
                        name="ข้อมูลตามกลุ่มค่าใช้จ่าย.xls"
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
      { text: 'ประเภท', value: 'GROUPS', align: 'center' },
      { text: 'ตรวจโดยแพทย์', value: 'DOCTORS', align: 'center' },
      { text: 'ตรวจโดยพยาบาล', value: 'NURSES', align: 'center' },
      { text: 'ว่าง', value: 'NULLS', align: 'center' },
      { text: 'ค่าใช้จ่าย', value: 'MONEYS', align: 'center' },
    ],

    opddata_detail: [],
    opddata_detail_thainame: [],
    loading: false,
    skeleton: true,
  }),
  computed: {
    monthstarts() {
      return this.$store.state.monthstart
    },
  },
  mounted() {
    this.fetch_opdetail()
    this.fetch_opdetail_thainame()
  },
  methods: {
    async fetch_opdetail() {
      await axios
        .get(`${this.$axios.defaults.baseURL}opdata_group.php`)
        .then((response) => {
          this.opddata_detail = response.data
          this.loading = true
          this.skeleton = false
        })
    },
    async fetch_opdetail_thainame() {
      await axios
        .get(`${this.$axios.defaults.baseURL}opdata_group_thainame.php`)
        .then((response) => {
          this.opddata_detail_thainame = response.data
          this.loading = true
          this.skeleton = false
        })
    },
  },
}
</script>
<style scoped></style>
