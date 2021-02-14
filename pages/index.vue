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
              <v-btn color="#fce38a" x-large dark>
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
                      <tr v-for="item in desserts" :key="item.name">
                        <td>{{ item.name }}</td>
                        <td>{{ item.calories }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.calories }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.calories }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.calories }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.calories }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.calories }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.calories }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.calories }}</td>
                        <td>{{ item.calories }}</td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card></v-flex
      >
    </v-layout>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    monthstart: new Date().toISOString().substr(0, 10),
    monthend: new Date().toISOString().substr(0, 10),
  }),
}
</script>
<style>
.v-input {
  font-size: 1.6em;
}
</style>
