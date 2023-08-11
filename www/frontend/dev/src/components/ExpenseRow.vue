<template>
    <tr class="expenses-list__row expenses-list-item">
        <td class="expenses-list-item__id">{{ $props.expense.id }}</td>
        <td class="expenses-list-item__comment">{{ $props.expense.comment }}</td>
        <td class="expenses-list-item__sum">{{ $props.expense.sum }}</td>
        <td class="expenses-list-item__date">{{ $props.expense.date }}</td>
        <td class="expenses-list-item__change"><input @click="changeItem($props.expense)" type="button" value="Удалить" /></td>
        <td class="expenses-list-item__delete"><input @click="this.$emit('deletedRow', $props.expense)" type="button" value="Удалить" /></td>
    </tr>
</template>

<script>
export default {
    emits: [
        'deletedRow'
    ],
    props: ['expense']
}
</script>