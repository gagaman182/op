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
                  <v-card-actions>
                    <v-spacer />
                    <v-btn v-if="loading" color="#1687a7" x-large dark>
                      <v-icon x-large> mdi-microsoft-excel </v-icon>
                      <export-excel :data="opddata_detail">
                        Excel
                      </export-excel>
                    </v-btn>
                  </v-card-actions>
                  <v-card-title>
                    <v-text-field
                      v-if="loading"
                      v-model="search"
                      append-icon="mdi-magnify"
                      label="ค้นหา"
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
  name: 'detail',
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
      { text: 'อาการสำคัญ', value: 'symptoms', align: 'center' },

      { text: 'รหัสโรค', value: 'DIAG_CODE_ALL', align: 'center' },
      { text: 'โรค', value: 'DIAG_NAME_ALL', align: 'center' },
      {
        text: 'กลุ่มผู้ให้บริการ',
        value: 'PROVIDER_CODE_ALL',
        align: 'center',
      },
      { text: 'ยา', value: 'DRUG_ALL', align: 'center' },
      { text: 'ราคายา', value: 'DRUG_PRICE_ALL', align: 'center' },
      { text: 'ยา24หลัก', value: 'DRUG_CODE24_ALL', align: 'center' },
      { text: 'หัตถการ', value: 'PROCED_ALL', align: 'center' },
      { text: 'ค่าหัตถการ', value: 'PROCED_PRICE_ALL', align: 'center' },
      { text: 'รหัสหัตถการ', value: 'PROCED_CODE_ALL', align: 'center' },
      { text: 'ฟัน', value: 'DENTAL_ALL', align: 'center' },
      { text: 'ค่าทำฟัน', value: 'DENTAL_PRICE_ALL', align: 'center' },
      { text: 'รหัสการทำฟัน', value: 'DENTAL_CODE_ALL', align: 'center' },
      { text: 'วัสดุสิ้นเปลือง', value: 'SUPPLIES_ALL', align: 'center' },
      {
        text: 'ค่าวัสดุสิ้นเปลือง',
        value: 'SUPPLIES_PRICE_ALL',
        align: 'center',
      },
      {
        text: 'รหัสวัสดุสิ้นเปลือง',
        value: 'SUPPLIES_CODE_ALL',
        align: 'center',
      },

      {
        text: 'ราการที่ไม่คิดค่าใช้จ่าย',
        value: 'DOCTOR_FEE',
        align: 'center',
      },
      {
        text: 'จำนวนเงินราการที่ไม่คิดค่าใช้จ่าย',
        value: 'DOCTOR_FEE_PRICE',
        align: 'center',
      },
      {
        text: 'รหัสราการที่ไม่คิดค่าใช้จ่าย',
        value: 'DOCTOR_FEE_CODE',
        align: 'center',
      },

      { text: 'แผนไทย', value: 'PAN_THAI_ALL', align: 'center' },
      { text: 'ค่าแผนไทย', value: 'PAN_THAI_PRICE', align: 'center' },
      { text: 'รหัสแผนไทย', value: 'PAN_THAI_CODE', align: 'center' },

      { text: 'สมุนไพร', value: 'DRUG_HERB_ALL', align: 'center' },
      { text: 'ค่าสมุนไพร', value: 'DRUG_HERB_PRICE', align: 'center' },
      { text: 'รหัสสมุนไพร', value: 'DRUG_HERB_CODE', align: 'center' },

      {
        text: 'จำนวนผู้รับบริการแผนไทย',
        value: 'PAN_THAI_VISIT',
        align: 'center',
      },
      { text: 'แผนไทย', value: 'PAN_THAI_NAME', align: 'center' },
      {
        text: 'แผนไทยผู้ให้บริการ',
        value: 'PROVIDER_PANTHAI',
        align: 'center',
      },
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
<style scoped>
table.v-table tbody td:first-child,
table.v-table tbody td:not(:first-child),
table.v-table tbody th:first-child,
table.v-table tbody th:not(:first-child),
table.v-table thead td:first-child,
table.v-table thead td:not(:first-child),
table.v-table thead th:first-child,
table.v-table thead th:not(:first-child) {
  padding: 0 10px;
}
</style>
