// Pteranodon Software. (https://pteranodon.com)
// Green: #189a1c
// Gray: hsl(211, 22%, 21%)

console.log(Pteranodon);

const suspended = Pteranodon.suspended;
const active = Pteranodon.servers.length - Pteranodon.suspended;
const freeDisk = Pteranodon.diskTotal - Pteranodon.diskUsed;
const freeMemory = Pteranodon.memoryTotal - Pteranodon.memoryUsed;

const diskChart = new Chart($("#disk_chart"), {
    type: "pie",
    data: {
        labels: ["Free Disk", "Used Disk"],
        datasets: [{
            backgroundColor: ["#189a1c", "hsl(211, 22%, 21%)"],
            data: [freeDisk, Pteranodon.diskUsed]
        }]
    }
});

const ramChart = new Chart($("#ram_chart"), {
    type: "pie",
    data: {
        labels: ["Free RAM", "Used RAM"],
        datasets: [{
            backgroundColor: ["#189a1c", "hsl(211, 22%, 21%)"],
            data: [freeMemory, Pteranodon.memoryUsed]
        }]
    }
});

const serversChart = new Chart($("#servers_chart"), {
    type: "pie",
    data: {
        labels: ["Active Servers", "Suspended Servers"],
        datasets: [{
            backgroundColor: ["#189a1c", "hsl(211, 22%, 21%)"],
            data: [active, suspended]
        }]
    }
});
