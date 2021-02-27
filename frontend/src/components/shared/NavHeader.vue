<template>


  
  <div>
    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#">
            <i class="fa fa-bars"></i>
          </a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown" v-if="notifications !== null">
          <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
            <i class="fa fa-product-hunt">&nbsp;&nbsp;&nbsp;&nbsp;</i>
            <span class="badge badge-danger navbar-badge">{{ notifications.low_products.length }}</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span
              class="dropdown-item dropdown-header"
            >{{ notifications.low_products.length }} productos bajos en stock</span>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item">
              <div class="media" v-for="(item, i) in products" :key="item.id">
                <img
                  src="../../assets/product_icon.jpg"
                  alt="User Avatar"
                  class="img-size-50 mr-3 img-circle"
                />
                <div class="media-body">
                  <h6 class="dropdown-item-title">
                    <span class="float-right text-sm text-danger">
                      <i class="fa fa-product-hunt"></i>
                      {{ item.stock }}
                    </span>
                  </h6>
                  <p class="text-sm">{{ item.name }}</p>
                </div>
              </div>
            </a>
            <div class="dropdown-divider"></div>
            <a
              href="#/product"
              class="dropdown-item dropdown-footer"
            >{{ notifications.low_products.length - products.length }} Productos mas</a>
          </div>
        </li>

        <li class="nav-item dropdown show" v-if="notifications !== null">
          <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
            <i class="fa fa-shopping-cart">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>
            <span class="badge badge-primary navbar-badge">{{ notifications.orders.length }}</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span
              class="dropdown-item dropdown-header"
            >{{ notifications.orders.length }} nuevos pedidos</span>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item">
              <i class="fa fa-apple mr-2"></i>
              {{ alimentacion }} alimentacion
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item">
              <i class="fa fa-file mr-2"></i>
              {{ gratuidad }} gratuidad
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item">
              <i class="fa fa-book mr-2"></i>
              {{ utiles }} utiles
            </a>
            <div class="dropdown-divider"></div>
            <a href="#/progressorder" class="dropdown-item dropdown-footer">Ver todos</a>
          </div>
        </li>

        <li class="nav-item">
          <a @click="logout" class="nav-link" href="#">
            <i class="fa fa-sign-out"></i>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {
  name: "NavHeader",
  data() {
    return {
      loading: false,
      notifications: null,
      alimentacion: 0,
      gratuidad: 0,
      utiles: 0,
      products: [],
    };
  },

  created() {
    let self = this;
    self.getNotifications();
  },

  methods: {
    logout() {
      let self = this;
      self.loading = true;
      self.$store.state.services.loginService
        .logout()
        .then((r) => {
          self.$store.dispatch("logout");
          self.$router.push("/login");
          self.loading = false;
        })
        .catch((e) => {});
    },

    getNotifications() {
      let self = this;
      self.loading = true;
      self.$store.state.services.reportService
        .getNotifications()
        .then((r) => {
          self.loading = false;
          self.notifications = r.data;
          self.alimentacion = self.notifications.orders.filter(
            (x) => x.type_order === "ALIMENTACION"
          ).length;
          self.gratuidad = self.notifications.orders.filter(
            (x) => x.type_order === "GRATUIDAD"
          ).length;
          self.utiles = self.notifications.orders.filter(
            (x) => x.type_order === "UTILES"
          ).length;
          self.products = self.notifications.low_products.filter(
            (item, idx) => idx < 5
          );
        })
        .catch((e) => {});
    },
  },
};
</script>

<style scoped>
.dropdown-menu-lg {
  max-width: 300px;
  min-width: 280px;
  padding: 0;
}
</style>
