<template>
  <v-layout justify-center align-center>
    <v-flex xs12>
      <v-card>
        <v-toolbar color="#95e1d3" dark>
          <v-card-title class="headline">
            สรุปค่ารักษาพยาบาลผู้ป่วยนอก
          </v-card-title>
        </v-toolbar>
        <v-card-text>
          <h3 class="pa-6">
            ผลการให้บริการรักษาผู้ป่วยนอก
            ของสถานบริการในเครือข่ายบริการปฐมภูมิโรงพยาบาลหาดใหญ่
          </h3>
          <hr />
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
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn color="#ff75a0" x-large dark>
            <fa icon="server" class="fa-2x" />
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-flex>
  </v-layout>
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
