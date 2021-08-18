const MINUTE = [
    { txt: "0分", val: 0 },
    { txt: "5分", val: 5 },
    { txt: "10分", val: 10 },
    { txt: "15分", val: 15 },
    { txt: "20分", val: 20 },
    { txt: "25分", val: 25 },
    { txt: "30分", val: 30 },
    { txt: "35分", val: 35 },
    { txt: "40分", val: 40 },
    { txt: "45分", val: 45 },
    { txt: "50分", val: 50 },
    { txt: "55分", val: 55 },
    { txt: "60分", val: 60 },
]

export default (context, inject) => {
    inject('MINUTE', MINUTE)
}