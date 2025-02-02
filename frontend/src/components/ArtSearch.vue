<template>
    <div class="searchBox">
        <div>
            <label for="department">Department: </label>
            <select v-model="selectedDepartment">
                <option v-for="dept in departments" :key="dept.departmentId" :value="dept.departmentId">
                    {{ dept.displayName }}
                </option>
            </select>
        </div>
        <div>
            <input v-model="query" placeholder="Search artwork title" @keyup.enter="searchArtworks" />
            <button @click="searchArtworks">Search</button>
        </div>
    </div>
</template>

<script>
import { ref, watch } from 'vue';

export default {
    props: {
        departments: Array,
        selectedDepartment: Number,
        query: String
    },
    setup(props, { emit }) {
        const selectedDepartment = ref(props.selectedDepartment);
        const query = ref(props.query);

        // Emit search request to parent component
        const searchArtworks = () => {
            emit('search', { departmentId: selectedDepartment.value, query: query.value });
        };

        // Watch for department changes and trigger search
        watch(selectedDepartment, (newDepartment) => {
            if (query.value.trim()) {
                searchArtworks();
            }
        });

        return {
            selectedDepartment,
            query,
            searchArtworks
        };
    }
};
</script>

<style scoped>
.searchBox {
    display: flex;
    justify-content: space-evenly;
}

select, input, button {
    padding: 8px 4px;
}
select {
    min-width: 275px;
}
</style>
