import { ChangeEvent, useState } from 'react'

export const Form303View = () => {

    const [cuota, setCuota] = useState(0)

    const handleChangeBaseImponible = (e: ChangeEvent<HTMLInputElement>) => {
        setCuota(parseInt(e.target.value) * 0.21)
    }

    return (
        <>
            <h1>Formulario 303</h1>
            <label htmlFor="base-imponible">Base imponible</label>
            <input id="base-imponible" onChange={handleChangeBaseImponible} />
            <label htmlFor="cuota">Cuota</label>
            <input id="cuota" value={cuota} readOnly />
        </>
    )
}
