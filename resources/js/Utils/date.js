export const formatDate = date => {
    const fecha = new Date(date);
    const opciones = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit"
    };

    return fecha.toLocaleString("es-CO", opciones);
}
