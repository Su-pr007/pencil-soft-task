<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          to="/"
          icon="home"
        />

        <q-toolbar-title>
          Pencil Soft Task
        </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <q-tabs align="left">
        <q-route-tab
        v-for="page of pages"
        :key="page"
        :to="page"
        :label="page.title"
        />
      </q-tabs>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { version } from 'ui' // "ui" is aliased in quasar.conf.js
import pages from '../router/pages';

export default {
  name: 'Layout',

  setup () {
    return {
      version,
      pages
    }
  }
}
</script>
