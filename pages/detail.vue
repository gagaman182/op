<template>
  <v-container grid-list-xl fluid>
    <v-layout row wrap justify-center align-center>
      <v-flex xs12>
        <v-card>
          <v-toolbar color="#95e1d3" dark>
            <v-card-title class="headline">
              <v-icon x-large class="fa-2x"> mdi-card-account-details </v-icon>
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
                      <export-excel :data="opddata_detail_thainame" name="รายละเอียดให้บริการ.xls" >
                        Excel
                      </export-excel>
                    </v-btn>
                  </v-card-actions>
                  <v-card-title>
                    <v-text-field
                       v-model="search"
             
                solo
                prepend-icon="mdi-card-account-details"
                placeholder="ค้นหา"
                hide-details
                class="hidden-sm-and-down"
                    ></v-text-field>
                  </v-card-title>
                  <v-data-table
                    width="20vh"
                    v-if="loading"
                    :headers="headers"
                    :items="opddata_detail"
                    :search="search"
                  ></v-data-table>
                 
                  </v-data-table>
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
      { text: 'รหัสสถานบริการ', value: 'HOSPCODE_FULL',width:400,align:'center'   },
      { text: 'HN', value: 'PID',width:150,align:'center' },
      { text: 'ลำดับรับบริการ', value: 'visitno',width:150,align:'center' },
      { text: 'วันที่รับบริการ', value: 'DATE_VISIT_THAI',width:150,align:'center' },
      { text: 'รหัสสิทธิ', value: 'INSTYPE',width:100,align:'center' },
      { text: 'สิทธิ', value: 'RIGHT_SERV',width:400,align:'center' },
      { text: 'กลุ่มสิทธิ', value: 'RIGHT_GROUP',width:100,align:'center' },
      { text: 'อาการสำคัญ', value: 'symptoms' ,width:400,align:'center'},
      { text: 'รหัสโรค', value: 'DIAG_CODE_ALL',width:150,align:'center' },
      { text: 'โรค', value: 'DIAG_NAME_ALL',width:500,align:'center' },
      { text: 'กลุ่มผู้ให้บริการ', value: 'PROVIDER_CODE_ALL',width:150 ,align:'center'},
      { text: 'ยา', value: 'DRUG_ALL',width:500,align:'center' },
      { text: 'ราคายา', value: 'DRUG_PRICE_ALL',width:150,align:'center' },
      { text: 'ยา24หลัก', value: 'DRUG_CODE24_ALL',width:500,align:'center' },
      { text: 'หัตถการ', value: 'PROCED_ALL',width:300,align:'center' },
      { text: 'ค่าหัตถการ', value: 'PROCED_PRICE_ALL',width:150,align:'center' },
      { text: 'รหัสหัตถการ', value: 'PROCED_CODE_ALL',width:200,align:'center' },
      { text: 'ฟัน', value: 'DENTAL_ALL',width:200,align:'center' },
      { text: 'ค่าทำฟัน', value: 'DENTAL_PRICE_ALL',width:150,align:'center' },
      { text: 'รหัสการทำฟัน', value: 'DENTAL_CODE_ALL' ,width:200,align:'center'},
      { text: 'วัสดุสิ้นเปลือง', value: 'SUPPLIES_ALL',width:300,align:'center' },
      { text: 'ค่าวัสดุสิ้นเปลือง', value: 'SUPPLIES_PRICE_ALL',width:150,align:'center' },
      { text: 'รหัสวัสดุสิ้นเปลือง', value: 'SUPPLIES_CODE_ALL',width:200,align:'center' },
      { text: 'ราการที่ไม่คิดค่าใช้จ่าย', value: 'DOCTOR_FEE',width:300,align:'center' },
      { text: 'จำนวนเงินราการที่ไม่คิดค่าใช้จ่าย', value: 'DOCTOR_FEE_PRICE',width:300,align:'center' },
      { text: 'รหัสราการที่ไม่คิดค่าใช้จ่าย', value: 'DOCTOR_FEE_CODE',width:300,align:'center' },
      { text: 'แผนไทย', value: 'PAN_THAI_ALL' ,width:300,align:'center'},
      { text: 'ค่าแผนไทย', value: 'PAN_THAI_PRICE',width:150,align:'center' },
      { text: 'รหัสแผนไทย', value: 'PAN_THAI_CODE',width:200,align:'center' },
      { text: 'สมุนไพร', value: 'DRUG_HERB_ALL',width:200,align:'center' },
      { text: 'ค่าสมุนไพร', value: 'DRUG_HERB_PRICE' ,width:150,align:'center'},
      { text: 'รหัสสมุนไพร', value: 'DRUG_HERB_CODE' ,width:200,align:'center'},
      { text: 'จำนวนผู้รับบริการแผนไทย', value: 'PAN_THAI_VISIT',width:300,align:'center' },
      { text: 'แผนไทย', value: 'PAN_THAI_NAME',width:150,align:'center' },
      { text: 'แผนไทยผู้ให้บริการ', value: 'PROVIDER_PANTHAI',width:200,align:'center' },
    ],

    opddata_detail: [],
    loading: false,
    skeleton: true,
    opddata_detail_thainame:[]
  }),

  mounted() {
    this.fetch_opdetail()
    this.fetch_opdetail_thainame()
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
      async fetch_opdetail_thainame() {
      await axios
        .get(`${this.$axios.defaults.baseURL}opdata_detail_thainame.php`)
        .then((response) => {
          this.opddata_detail_thainame = response.data
       
        })
    },

   
   
  },
}
</script>
<style scoped>table.v-table thead th {
      font-size: 20px !important;

 }</style>
