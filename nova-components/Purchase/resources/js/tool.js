import navigation from './components/layouts/navigation';

Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'purchases',
      path: '/purchase',
      component: require('./views/purchases/'),
    },
    {
      name: 'createpurchase',
      path: '/purchase/create',
      component: require('./views/purchases/form'),
    },
    {
      name: 'editpurchase',
      path: '/purchase/:id/edit',
      meta: {
        mode: 'edit'
      },
      component: require('./views/purchases/form'),
    },
    {
      name: 'showpurchase',
      path: '/purchase/:id', 
      component: require('./views/purchases/show')
    },
    {
      name: 'sales',
      path: '/invoices',
      component: require('./views/sales/index.vue')
    },
    {
      name: 'createsale',
      path: '/invoices/create',
      component: require('./views/sales/form'),
    },
    {
      name: 'showsale',
      path: '/invoices/:id', 
      component: require('./views/sales/show')
    },
    {
      name: 'indexsettings',
      path: '/settings',
      component: require('./views/settings/')
    },
    {
      name: 'company',
      path: '/settings/company',
      component: require('./views/settings/general')
    },
    {
      name: 'invoices',
      path: '/settings/invoices',
      component: require('./views/settings/invoices')
    },
    {
      name: 'default',
      path: '/settings/default',
      component: require('./views/settings/default')
    }
  ])
})
Nova.booting((Vue, router) => {
  Vue.component('grouped-navigation', navigation);
});
