<template>
  <q-page padding>
    <h1>Расходы</h1>
    <ExpensesList></ExpensesList>
  </q-page>
</template>

<script>
import ExpensesList from '../components/ExpensesList.vue';

export default {
  components: [
    ExpensesList
  ]
}
</script>