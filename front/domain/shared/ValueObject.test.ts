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
    test(`two value objects with the same props are the same.`, () => {
        const mock1 = new ValueObjectMock({ value: 'Foo' })
        const mock2 = new ValueObjectMock({ value: 'Foo' })

        expect(mock1.equals(mock2)).toBe(true)
    })

    test(`two value objects with the different props are different.`, () => {
        const mock1 = new ValueObjectMock({ value: 'Foo'})
        const mock2 = new ValueObjectMock({ value: 'Bar' })

        expect(mock1.equals(mock2)).toBe(false)
    })
})