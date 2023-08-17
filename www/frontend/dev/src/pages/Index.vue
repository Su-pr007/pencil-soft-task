<template>
  <q-page padding>
    <h1>Расходы</h1>
    <div class="expenses-container">
      <ExpensesList></ExpensesList>
    </div>
  </q-page>
</template>

<script setup>
import ExpensesList from '../components/ExpensesList.vue';
</script>

<style lang="sass" scoped>
.expenses-container
  min-width: 750px
  width: 50%
</style>