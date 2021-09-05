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
const POINTS = [
    { txt: "1pt", val: 1 },
    { txt: "2pt", val: 2 },
    { txt: "3pt", val: 3 },
    { txt: "4pt", val: 4 },
    { txt: "5pt", val: 5 },
    { txt: "6pt", val: 6 },
    { txt: "7pt", val: 7 },
    { txt: "8pt", val: 8 },
    { txt: "9pt", val: 9 },
    { txt: "10pt", val: 10 },
]

export default (context, inject) => {
    inject('MINUTE', MINUTE)
    inject('POINTS', POINTS)
}