import moment from 'moment'

export const state = () => ({
    users: [],
    tasks: [],
    works: [],
})

export const mutations = {
    setUsers(state, users) {
        state.users = users
    },
    setTasks(state, tasks) {
        state.tasks = tasks
    },
    setWorks(state, works) {
        state.works = works
    },
}

export const actions = {
    setUsers({ commit }) {
        let users = []
        for (let i = 1; i <= 3; i++) {
            users.push({
                id: i,
                name: 'name' + i,
                email: 'email' + i,
                password: 'password' + i,
                color: 'color' + i,
                room_id: 1,
            });
        }
        commit('setUsers', users)
    },
    setTasks({ commit }) {
        let tasks = []
        for (let i = 1; i <= 7; i++) {
            tasks.push({
                id: i,
                name: "title of task" + i,
                defaultMinute: 5,
                pointPerMinute: 1,
                is_everyDay: true,
                room_id: 1,
            });
        }
        for (let i = 1; i <= 3; i++) {
            tasks.push({
                id: i + 7,
                name: "title of task" + Number(i + 7),
                defaultMinute: 5,
                pointPerMinute: 1,
                is_everyDay: false,
                room_id: 1,
            });
        }
        commit('setTasks', tasks)
    },
    setWorks({ commit }) {
        let works = []
        for (let i = 1; i <= 5; i++) {
            works.push({
                id: i,
                task_id: i,
                user_id: (i % 3) + 1,
                minute: i * 5,
                room_id: 1,
                date: moment(new Date()).format('YYYY-MM-DD'),
            });
        }
        for (let i = 1; i <= 2; i++) {
            works.push({
                id: i,
                task_id: i,
                user_id: i,
                minute: i * 5,
                room_id: 1,
                date: moment(new Date()).format('YYYY-MM-DD'),
            });
        }
        commit('setWorks', works)
    },
}