import { ChangeEvent, useState } from 'react'

export const Form303View = () => {

    const [cuota, setCuota] = useState(0)

    const handleChangeBaseImponible = (e: ChangeEvent<HTMLInputElement>) => {
        setCuota(parseInt(e.target.value) * 0.21)
    }

    return (
        <>
            <h1>Formulario 303</h1>
            <label htmlFor="nif">nif</label>
            <input id="nif" />
            <label htmlFor="razon-social">Razon social</label>
            <input id="razon-social" />
            <label htmlFor="ejercicio">Ejercicio</label>
            <input id="ejercicio" />
            <label htmlFor="periodo">Período</label>
            <input id="periodo" />
            <label htmlFor="base-imponible">Base imponible</label>
            <input id="base-imponible" onChange={handleChangeBaseImponible} />
            <label htmlFor="cuota">Cuota</label>
            <input id="cuota" value={cuota} readOnly />
            <label htmlFor="base-imponible-deducible">Base imponible (Deducible)</label>
            <input id="base-imponible-deducible" />
        </>
    )
}
