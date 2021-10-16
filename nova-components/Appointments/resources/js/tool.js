Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'appointments',
      path: '/appointments',
      component: require('./views/appoiments/'),
    },
  ])
})
