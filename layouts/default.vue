<template>
  <v-app dark>
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant="miniVariant"
      :clipped="clipped"
      fixed
      app
      color="#fce38a"
    >
      <v-list>
        <v-list-item
          v-for="(item, i) in items"
          :key="i"
          :to="item.to"
          router
          exact
        >
          <v-list-item-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title v-text="item.title" />
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-app-bar :clipped-left="clipped" fixed app color="#ff75a0" dark>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-btn icon @click.stop="miniVariant = !miniVariant">
        <v-icon>mdi-{{ `chevron-${miniVariant ? 'right' : 'left'}` }}</v-icon>
      </v-btn>

      <v-toolbar-title v-text="title" />
      <v-spacer />
    </v-app-bar>
    <v-main>
      <v-container>
        <nuxt />
      </v-container>
    </v-main>
    <v-navigation-drawer v-model="rightDrawer" :right="right" temporary fixed>
      <v-list>
        <v-list-item @click.native="right = !right">
          <v-list-item-action>
            <v-icon light> mdi-repeat </v-icon>
          </v-list-item-action>
          <v-list-item-title>Switch drawer (click me)</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-footer :absolute="!fixed" app>
      <span>&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      clipped: false,
      drawer: true,
      fixed: true,
      items: [
        {
          icon: 'mdi-home-circle  ',
          title: 'หน้าหลัก',
          to: '/',
        },
        {
          icon: 'mdi-card-account-details ',
          title: 'รายละเอียด',
          to: '/detail',
        },
        {
          icon: 'mdi-cash-multiple ',
          title: 'กลุ่มค่าใช้จ่าย',
          to: '/all',
        },
        {
          icon: 'mdi-pill  ',
          title: 'ยาเรื้อรัง',
          to: '/drug24',
        },
      ],
      miniVariant: true,
      right: true,
      rightDrawer: false,
      title:
        'ระบบประมวลผลข้อมูลการให้บริการผู้ป่วยนอก เครือข่ายปฐมภูมิโรงพยาบาลหาดใหญ่',
    }
  },
}
</script>
<style>
body {
  font-family: 'Chakra Petch', sans-serif;
}
</style>
