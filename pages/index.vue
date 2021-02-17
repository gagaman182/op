<template>
  <v-container grid-list-xl fluid>
    <v-layout row wrap justify-center align-center>
      <v-flex xs12>
        <v-card>
          <v-toolbar color="#95e1d3" dark>
            <v-card-title class="headline">
              <v-icon x-large> mdi-monitor-dashboard </v-icon>
              &nbsp; สรุปค่ารักษาพยาบาลผู้ป่วยนอก
            </v-card-title>
          </v-toolbar>
          <v-card-text>
            <v-row>
              <v-col cols="12" md="6" class="text-center">
                <h2 class="pa-6">เดือนเริ่มต้น</h2>

                <v-date-picker
                  v-model="monthstart"
                  color="#95e1d3"
                  locale="th-TH"
                  type="month"
                ></v-date-picker>
              </v-col>
              <v-col cols="12" md="6" class="text-center">
                <h2 class="pa-6">เดือนสิ้นสุด</h2>
                <v-date-picker
                  v-model="monthend"
                  color="#95e1d3"
                  locale="th-TH"
                  type="month"
                ></v-date-picker>
              </v-col>
            </v-row>
            <v-card-actions>
              <v-spacer />
              <v-btn color="#fce38a" @click="runop()" x-large dark>
                <fa icon="server" class="fa-2x" />
              </v-btn>
            </v-card-actions>
          </v-card-text>
        </v-card>
      </v-flex>

      <v-flex xs12>
        <v-card>
          <v-toolbar color="#95e1d3" dark>
            <v-card-title class="headline">
              <v-icon x-large class="fa-2x"> mdi-folder-table-outline </v-icon>
              &nbsp; ตารางคำนวณค่ารักษา OPD ผู้ป่วยบัตรทอง
            </v-card-title>
          </v-toolbar>

          <v-card-text>
            <v-row>
              <v-col cols="12" md="12" class="text-center">
                <v-simple-table>
                  <template v-slot:default>
                    <thead>
                      <tr>
                        <v-card-actions>
                          <h3 v-if="skeleton">
                            <v-skeleton-loader
                              class="mx-auto"
                              max-width="100"
                              type="text"
                            ></v-skeleton-loader>
                          </h3>
                          <v-alert v-if="loading" outlined color="#9a8194" text>
                            <h3>
                              ข้อมูลถูกประมวลในเดือน {{ months_1 }} &nbsp;{{
                                years_1
                              }}
                              {{ center }}&nbsp; {{ months_2 }} &nbsp;{{
                                years_2
                              }}
                            </h3>
                          </v-alert>

                          <v-spacer />
                          <v-btn color="#1687a7" x-large dark>
                            <v-icon x-large> mdi-microsoft-excel </v-icon>
                          </v-btn>
                        </v-card-actions>
                      </tr>
                    </thead>
                  </template>
                </v-simple-table>
              </v-col>
              <v-col cols="12" md="12" class="text-center">
                <v-simple-table>
                  <template v-slot:default>
                    <thead>
                      <tr>
                        <th class="text-center" colspan="16">
                          <!-- <h2>คำนวณค่ารักษา OPD ผู้ป่วยบัตรทอง</h2> -->
                          <!-- <v-chip color="#ff75a0" label dark>
                            คำนวณค่ารักษา OPD ผู้ป่วยบัตรทอง
                          </v-chip> -->

                          <v-alert border="top" color="#ff75a0 " dark>
                            <h2>คำนวณค่ารักษา OPD ผู้ป่วยบัตรทอง</h2>
                          </v-alert>
                        </th>
                      </tr>

                      <tr>
                        <th class="text-center" rowspan="2">
                          <v-toolbar color="#fce38a" dark>
                            <v-card-title class="headline">
                              สถานบริการ</v-card-title
                            >
                          </v-toolbar>
                        </th>
                        <th class="text-center" colspan="3">
                          <v-toolbar color="#fce38a" dark>
                            <v-spacer />
                            <v-card-title class="headline">
                              ค่าใช้จ่ายจริง</v-card-title
                            >
                            <v-spacer />
                          </v-toolbar>
                        </th>
                        <th class="text-center" colspan="3">
                          <v-toolbar color="#fce38a" dark>
                            <v-spacer />
                            <v-card-title class="headline">
                              ตรวจโดย</v-card-title
                            >
                            <v-spacer />
                          </v-toolbar>
                        </th>
                        <th class="text-center" colspan="3">
                          <v-toolbar color="#fce38a" dark>
                            <v-spacer />
                            <v-card-title class="headline">
                              โรคทั่วไป</v-card-title
                            >
                            <v-spacer />
                          </v-toolbar>
                        </th>
                        <th class="text-center" colspan="3">
                          <v-toolbar color="#fce38a" dark>
                            <v-spacer />
                            <v-card-title class="headline">
                              โรคเรื้อรัง</v-card-title
                            >
                            <v-spacer />
                          </v-toolbar>
                        </th>
                        <th class="text-center" colspan="3">
                          <v-toolbar color="#fce38a" dark>
                            <v-spacer />
                            <v-card-title class="headline text-center">
                              ฟัน</v-card-title
                            >
                            <v-spacer />
                          </v-toolbar>
                        </th>
                      </tr>

                      <tr>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark> คน </v-chip>
                        </th>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark> ครั้ง </v-chip>
                        </th>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark> บาท </v-chip>
                        </th>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark> แพทย์ </v-chip>
                        </th>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark> จนท. </v-chip>
                        </th>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark> รวม </v-chip>
                        </th>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark> แพทย์ </v-chip>
                        </th>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark> จนท. </v-chip>
                        </th>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark> รวม </v-chip>
                        </th>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark> แพทย์ </v-chip>
                        </th>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark> จนท. </v-chip>
                        </th>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark> รวม </v-chip>
                        </th>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark>
                            ทันตแพทย์
                          </v-chip>
                        </th>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark>
                            ทันตาภิบาล
                          </v-chip>
                        </th>
                        <th class="text-center">
                          <v-chip color="#ff75a0" label dark> รวม </v-chip>
                        </th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr v-if="skeleton">
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                        <td>
                          <v-skeleton-loader
                            class="mx-auto"
                            max-width="50"
                            type="text"
                          ></v-skeleton-loader>
                        </td>
                      </tr>
                      <tr
                        v-if="loading"
                        v-for="item in opddata"
                        :key="item.name"
                      >
                        <td>
                          <h4>{{ item.HOSPCODE }}</h4>
                        </td>
                        <td>
                          <H3>{{ item.PERSON }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.VISIT }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.MONEYS }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.DOCTORS }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.NURSES }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.DOCTORANDNURSE }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.GENERAL_DOCTOR }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.GENERAL_NURSE }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.GENERAL_DOCTOR_NURSE }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.CHRONIC_DOCTOR }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.CHRONIC_NURSE }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.CHRONIC_DOCTOR_NURSE }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.DENT_DOCTORS }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.DENT_NURSES }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.DENT_DOCTORANDNURSE }}</H3>
                        </td>
                        <td>
                          <H3>{{ item.calories }}</H3>
                        </td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card></v-flex
      >
      <v-dialog v-model="dialog" hide-overlay persistent width="300">
        <v-card color="#ff75a0" dark>
          <v-card-text>
            ระบบกำลังประมวลผล
            <v-progress-linear
              indeterminate
              color="white"
              class="mb-0"
            ></v-progress-linear>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-layout>
  </v-container>
</template>

<script>
import axios from 'axios'

export default {
  data: () => ({
    monthstart: new Date().toISOString().substr(0, 7),
    monthend: new Date().toISOString().substr(0, 7),
    message: '',
    dialog: false,
    opddata: '',
    loading: false,
    skeleton: true,
    months_1: '0',
    years_1: '0',
    months_2: '0',
    years_2: '0',
    center: '-',
  }),

  mounted() {
    this.fetch_op()
  },
  methods: {
    runop() {
      this.dialog = true
      this.loading = false
      this.skeleton = true
      if (this.monthend < this.monthstart) {
        this.$swal({
          title: 'แจ้งเตือน',
          text: 'ระบุเดือนไม่ถูกต้อง',
          icon: 'error',
          confirmButtonText: 'ตกลง',
        })
      } else {
        axios
          .post(`${this.$axios.defaults.baseURL}runop.php`, {
            monthstart: this.monthstart,
            monthend: this.monthend,
          })
          .then((response) => {
            this.message = response.data
            if (this.message[0].message === 'เพิ่มข้อมูลสำเร็จ') {
              this.$swal({
                title: 'สถานะการเพิ่ม',
                text: this.message[0].message,
                icon: 'success',
                confirmButtonText: 'ตกลง',
              })
              this.dialog = false
              this.loading = true
              this.skeleton = false
              this.fetch_op()
            } else {
              this.$swal({
                title: 'สถานะการเพิ่ม',
                text: this.message[0].message,
                icon: 'error',
                confirmButtonText: 'ตกลง',
              })
            }
          })
      }
    },
    async fetch_op() {
      await axios
        .get(`${this.$axios.defaults.baseURL}opdata.php`)
        .then((response) => {
          this.opddata = response.data
          this.loading = true
          this.skeleton = false
          this.years_1 = this.opddata[0].YEARS_1
          this.years_2 = this.opddata[0].YEARS_2

          //YEAR1
          if (this.opddata[0].MONTHS_1 == 'January') {
            this.months_1 = 'มกราคม'
          } else if (this.opddata[0].MONTHS_1 == 'February') {
            this.months_1 = 'กุมภาพันธ์'
          } else if (this.opddata[0].MONTHS_1 == 'March') {
            this.months_1 = 'มีนาคม'
          } else if (this.opddata[0].MONTHS_1 == 'April') {
            this.months_1 = 'เมษายน'
          } else if (this.opddata[0].MONTHS_1 == 'May') {
            this.months_1 = 'พฤษภาคม'
          } else if (this.opddata[0].MONTHS_1 == 'June') {
            this.months_1 = 'มิถุนายน'
          } else if (this.opddata[0].MONTHS_1 == 'July') {
            this.months_1 = 'กรกฏาคม'
          } else if (this.opddata[0].MONTHS_1 == 'August') {
            this.months_1 = 'สิงหาคม'
          } else if (this.opddata[0].MONTHS_1 == 'September') {
            this.months_1 = 'กันยายน'
          } else if (this.opddata[0].MONTHS_1 == 'October') {
            this.months_1 = 'ตุลาคม'
          } else if (this.opddata[0].MONTHS_1 == 'November') {
            this.months_1 = 'พฤศจิการยน'
          } else if (this.opddata[0].MONTHS_1 == 'December') {
            this.months_1 = 'ธันวาคม'
          }

          //YEAR2
          if (this.opddata[0].MONTHS_2 == 'January') {
            this.months_2 = 'มกราคม'
          } else if (this.opddata[0].MONTHS_2 == 'February') {
            this.months_1 = 'กุมภาพันธ์'
          } else if (this.opddata[0].MONTHS_2 == 'March') {
            this.months_2 = 'มีนาคม'
          } else if (this.opddata[0].MONTHS_2 == 'April') {
            this.months_2 = 'เมษายน'
          } else if (this.opddata[0].MONTHS_2 == 'May') {
            this.months_2 = 'พฤษภาคม'
          } else if (this.opddata[0].MONTHS_2 == 'June') {
            this.months_2 = 'มิถุนายน'
          } else if (this.opddata[0].MONTHS_2 == 'July') {
            this.months_2 = 'กรกฏาคม'
          } else if (this.opddata[0].MONTHS_2 == 'August') {
            this.months_2 = 'สิงหาคม'
          } else if (this.opddata[0].MONTHS_2 == 'September') {
            this.months_2 = 'กันยายน'
          } else if (this.opddata[0].MONTHS_2 == 'October') {
            this.months_2 = 'ตุลาคม'
          } else if (this.opddata[0].MONTHS_2 == 'November') {
            this.months_2 = 'พฤศจิการยน'
          } else if (this.opddata[0].MONTHS_2 == 'December') {
            this.months_2 = 'ธันวาคม'
          }

          if (this.months_1 == this.months_2) {
            this.months_2 = ''
            this.years_2 = ''
            this.center = ''
          }
        })
    },
  },
}
</script>
<style>
.v-input {
  font-size: 1.6em;
}
</style>
