import { describe, expect, test } from 'vitest'
import { ValueObject } from './ValueObject'

interface ValueObjectMockProps {
    value: string
}

class ValueObjectMock extends ValueObject<ValueObjectMockProps> {
    constructor(props: ValueObjectMockProps) {
        super(props)
    }
}

describe(`ValueObject class`, () => {
    test(`two value objects with the same props are the same object`, () => {
        const mock1 = new ValueObjectMock({ value: 'Foo Bar' })
        const mock2 = new ValueObjectMock({ value: 'Foo Bar' })

        expect(mock1.equals(mock2)).toBe(true)
    })
})