<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
      :clipped="$vuetify.breakpoint.lgAndUp"
      width="300"
    >
      <v-img
        :aspect-ratio="16 / 9"
        src="https://cdn.vuetifyjs.com/images/backgrounds/bg-2.jpg"
      >
        <v-row align="end" class="lightbox white--text pa-2 fill-height">
          <v-col>
            <v-list-item-avatar>
              <img :src="getImage" :alt="userName" />
            </v-list-item-avatar>

            <v-list-item-content>
              <v-list-item-title>{{ userName }}</v-list-item-title>
            </v-list-item-content>
          </v-col>
        </v-row>
      </v-img>
      <v-list dense>
        <v-list-group
          v-for="item in getMenu"
          :key="item.text"
          prepend-icon="mdi-youtube"
          no-action
        >
          <template v-slot:activator>
            <v-list-item-content>
              <v-list-item-title v-text="item.text"></v-list-item-title>
            </v-list-item-content>
          </template>

          <v-list-item v-for="subItem in item.childrens" :key="subItem.text">
            <v-list-item-content>
              <v-list-item-title v-text="subItem.text"></v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar app :clipped-left="$vuetify.breakpoint.lgAndUp" dense>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title class="mr-12 align-center">
        <v-avatar size="32px">
          <v-img
            src="https://cdn.vuetifyjs.com/images/logos/logo.png"
            alt="Vuetify"
          ></v-img>
        </v-avatar>
        <span class="title">Sistema MRM</span>
      </v-toolbar-title>
    </v-app-bar>
  </v-app>
  <!-- Main Sidebar Container 
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo 
    <a href="#/" class="brand-link">
      <img src="../../assets/logo.jpeg" alt="Logo" class="brand-image elevation-3" />
      <span class="brand-text font-weight-light">DISPROVASA</span>
    </a>

    <!-- Sidebar 
    <div class="sidebar">
      <!-- Sidebar user panel (optional) 
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img :src="getImage" class="img-circle elevation-2" alt="User Image" />
        </div>
        <div class="info">
          <a href="#" class="d-block">
            <small>{{userName}}</small>
          </a>
        </div>
      </div>

      <!-- Sidebar Menu 
      <nav class="mt-2">
        <ul
          class="nav nav-pills nav-sidebar flex-column nav-child-indent"
          data-widget="treeview"
          role="menu"
          data-accordion="false"
        >
          <!-- Add icons to the links using the .nav-icon class
          with font-awesome or any other icon font library

          <li class="nav-item">
            <a href="#/" class="nav-link">
              <i class="nav-icon fa fa-home"></i>
              <p>
                Home
                <span class="right badge badge-danger">
                  <i class="fa fa-home"></i>
                </span>
              </p>
            </a>
          </li>
          <template>
            <li class="nav-item" v-for="item in getMenu" :key="item.text">
              <a :href="'#/'+item.path" class="nav-link" v-if="item.childrens.length === 0">
                <i :class="'nav-icon fa fa-'+item.icon"></i>
                <p>{{item.text}}</p>
              </a>
              <a href="#" class="nav-link" v-if="item.childrens.length > 0">
                <i :class="'nav-icon fa fa-'+item.icon"></i>
                <p>
                  {{item.text}}
                  <i class="right fa fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview" v-for="child in item.childrens" :key="child.text">
                <li class="nav-item">
                  <a :href="'#/'+child.path" class="nav-link">
                    <i :class="'fa fa-'+child.icon+' nav-icon'"></i>
                    <p>{{child.text}}</p>
                  </a>
                </li>
              </ul>
            </li>
          </template>
        </ul>
      </nav>
      <!-- /.sidebar-menu
    </div>
    <!-- /.sidebar 
  </aside>-->
</template>

<script>
export default {
  name: 'NavegationMenu',
  data: () => ({
    drawer: null,
  }),

  methods: {
    redirect(path) {
      if (path === undefined) return
      this.$router.push(path)
    },
  },

  mounted() {
    let self = this
    $('body').resize()
  },

  computed: {
    userName() {
      let self = this
      var user = self.$store.state.usuario
      if (!_.isEmpty(user)) {
        return (
          self.$store.state.usuario.people.name_one +
          ' ' +
          self.$store.state.usuario.people.last_name_one
        )
      }
      return ''
    },

    getMenu() {
      let self = this
      return self.$store.state.menu
    },

    getImage() {
      let self = this
      return self.$store.state.base_url + 'img/user_empty.jpg'
    },
  },
}
</script>
<style scoped>
.brand-image {
  border-radius: 10%;
}
</style>
