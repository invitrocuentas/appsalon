let paso = 1
const cita = {
    nombre : "",
    fecha : "",
    hora : "",
    servicios : []
}
document.addEventListener("DOMContentLoaded", ()=>{
    iniciarApp()
})


const iniciarApp = () => {
    mostrarSeccion()
    tabs();

    // Consultar Api
    consultarApi()

    nombreCliente()
    seleccionarFecha()
}

const mostrarSeccion = () => {
    
    // Ocultar seccion que tenga clase mostrar
    const seccionAnterior = document.querySelector(".mostrar")
    if(seccionAnterior){
        seccionAnterior.classList.remove("mostrar")
    }

    // Seleccionar seccion con el paso
    const seccion = document.querySelector(`#paso-${paso}`)
    seccion.classList.add("mostrar")

    // Quitrar clase actuyal a btn anterior
    const tabAnterior = document.querySelector(".actual")
    if(tabAnterior){
        tabAnterior.classList.remove('actual')
    }

    // Resalta button seleccionado
    const tab = document.querySelector(`[data-paso="${paso}"]`)
    tab.classList.add("actual")
}

const tabs = () => {
    const botones = document.querySelectorAll(".tabs button")
    botones.forEach(boton => {
        boton.addEventListener("click", (e)=>{
            paso = parseInt(e.target.dataset.paso)
            mostrarSeccion()
        })
    })
}

const consultarApi = async() => {
    try {
        const url = "http://localhost:3000/api/servicios"
        const respuesta = await fetch(url)
        const data = await respuesta.json()
        mostrarServicios(data)
    } catch (error) {
        console.log(error)
    }
}

const mostrarServicios = (servicios) => {
    servicios.forEach(servicio => {
        const {id, nombre, precio} = servicio
        const nombreServicio = document.createElement("p")
        nombreServicio.classList.add("nombre-servicio")
        nombreServicio.textContent = nombre

        const precioServicio = document.createElement('p')
        precioServicio.classList.add("precio-servicio")
        precioServicio.textContent = `$${precio}`

        const  servicioDiv = document.createElement("div")
        servicioDiv.classList.add("servicio")
        servicioDiv.dataset.idServicio = id

        servicioDiv.onclick = function(){
            seleccionarServicio(servicio)
        }

        servicioDiv.appendChild(nombreServicio)
        servicioDiv.appendChild(precioServicio)

        const servicios = document.querySelector("#servicios")
        servicios.appendChild(servicioDiv)


    })
}

const seleccionarServicio = (servicio) => {
    const {id} = servicio
    const {servicios} = cita

    const existe = servicios.some(servicio => servicio.id === id)
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`)
    if(existe){
        cita.servicios = servicios.filter(servicio => servicio.id !== id)
        divServicio.classList.remove('seleccionado')
    }else{
        cita.servicios = [
            ...servicios,
            servicio
        ]
        divServicio.classList.add('seleccionado')
    }
}

const nombreCliente = () => {
    const nombre = document.querySelector("#nombre").value
    cita.nombre = nombre
}

const seleccionarFecha = () => {
    const inputFecha = document.querySelector("#fecha")
    inputFecha.addEventListener("input", (e)=>{
        cita.fecha = e.target.value
    })
}