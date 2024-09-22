<?php
/**
 * InstrumentEntity
 *
 * PHP version 7.4
 *
 * @category Class
 * @package  Cashfree
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Cashfree Payment Gateway APIs
 *
 * Cashfree's Payment Gateway APIs provide developers with a streamlined pathway to integrate advanced payment processing capabilities into their applications, platforms and websites.
 *
 * The version of the OpenAPI document: 2023-08-01
 * Contact: developers@cashfree.com
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 7.0.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Cashfree\Model;

use \ArrayAccess;
use \Cashfree\ObjectSerializer;

/**
 * InstrumentEntity Class Doc Comment
 *
 * @category Class
 * @description Saved card instrument object
 * @package  Cashfree
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class InstrumentEntity implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'InstrumentEntity';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'customer_id' => 'string',
        'afa_reference' => 'string',
        'instrument_id' => 'string',
        'instrument_type' => 'string',
        'instrument_uid' => 'string',
        'instrument_display' => 'string',
        'instrument_status' => 'string',
        'created_at' => 'string',
        'instrument_meta' => '\Cashfree\Model\SavedInstrumentMeta'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'customer_id' => null,
        'afa_reference' => null,
        'instrument_id' => null,
        'instrument_type' => null,
        'instrument_uid' => null,
        'instrument_display' => null,
        'instrument_status' => null,
        'created_at' => null,
        'instrument_meta' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static $openAPINullables = [
        'customer_id' => false,
		'afa_reference' => false,
		'instrument_id' => false,
		'instrument_type' => false,
		'instrument_uid' => false,
		'instrument_display' => false,
		'instrument_status' => false,
		'created_at' => false,
		'instrument_meta' => false
    ];

    /**
      * If a nullable field gets set to null, insert it here
      *
      * @var boolean[]
      */
    protected $openAPINullablesSetToNull = [];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of nullable properties
     *
     * @return array
     */
    protected static function openAPINullables(): array
    {
        return self::$openAPINullables;
    }

    /**
     * Array of nullable field names deliberately set to null
     *
     * @return boolean[]
     */
    private function getOpenAPINullablesSetToNull(): array
    {
        return $this->openAPINullablesSetToNull;
    }

    /**
     * Setter - Array of nullable field names deliberately set to null
     *
     * @param boolean[] $openAPINullablesSetToNull
     */
    private function setOpenAPINullablesSetToNull(array $openAPINullablesSetToNull): void
    {
        $this->openAPINullablesSetToNull = $openAPINullablesSetToNull;
    }

    /**
     * Checks if a property is nullable
     *
     * @param string $property
     * @return bool
     */
    public static function isNullable(string $property): bool
    {
        return self::openAPINullables()[$property] ?? false;
    }

    /**
     * Checks if a nullable property is set to null.
     *
     * @param string $property
     * @return bool
     */
    public function isNullableSetToNull(string $property): bool
    {
        return in_array($property, $this->getOpenAPINullablesSetToNull(), true);
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'customer_id' => 'customer_id',
        'afa_reference' => 'afa_reference',
        'instrument_id' => 'instrument_id',
        'instrument_type' => 'instrument_type',
        'instrument_uid' => 'instrument_uid',
        'instrument_display' => 'instrument_display',
        'instrument_status' => 'instrument_status',
        'created_at' => 'created_at',
        'instrument_meta' => 'instrument_meta'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'customer_id' => 'setCustomerId',
        'afa_reference' => 'setAfaReference',
        'instrument_id' => 'setInstrumentId',
        'instrument_type' => 'setInstrumentType',
        'instrument_uid' => 'setInstrumentUid',
        'instrument_display' => 'setInstrumentDisplay',
        'instrument_status' => 'setInstrumentStatus',
        'created_at' => 'setCreatedAt',
        'instrument_meta' => 'setInstrumentMeta'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'customer_id' => 'getCustomerId',
        'afa_reference' => 'getAfaReference',
        'instrument_id' => 'getInstrumentId',
        'instrument_type' => 'getInstrumentType',
        'instrument_uid' => 'getInstrumentUid',
        'instrument_display' => 'getInstrumentDisplay',
        'instrument_status' => 'getInstrumentStatus',
        'created_at' => 'getCreatedAt',
        'instrument_meta' => 'getInstrumentMeta'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }

    public const INSTRUMENT_TYPE_CARD = 'card';
    public const INSTRUMENT_TYPE_UNKNOWN_DEFAULT_OPEN_API = 'unknown_default_open_api';
    public const INSTRUMENT_STATUS_ACTIVE = 'ACTIVE';
    public const INSTRUMENT_STATUS_INACTIVE = 'INACTIVE';
    public const INSTRUMENT_STATUS_UNKNOWN_DEFAULT_OPEN_API = 'unknown_default_open_api';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getInstrumentTypeAllowableValues()
    {
        return [
            self::INSTRUMENT_TYPE_CARD,
            self::INSTRUMENT_TYPE_UNKNOWN_DEFAULT_OPEN_API,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getInstrumentStatusAllowableValues()
    {
        return [
            self::INSTRUMENT_STATUS_ACTIVE,
            self::INSTRUMENT_STATUS_INACTIVE,
            self::INSTRUMENT_STATUS_UNKNOWN_DEFAULT_OPEN_API,
        ];
    }

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->setIfExists('customer_id', $data ?? [], null);
        $this->setIfExists('afa_reference', $data ?? [], null);
        $this->setIfExists('instrument_id', $data ?? [], null);
        $this->setIfExists('instrument_type', $data ?? [], null);
        $this->setIfExists('instrument_uid', $data ?? [], null);
        $this->setIfExists('instrument_display', $data ?? [], null);
        $this->setIfExists('instrument_status', $data ?? [], null);
        $this->setIfExists('created_at', $data ?? [], null);
        $this->setIfExists('instrument_meta', $data ?? [], null);
    }

    /**
    * Sets $this->container[$variableName] to the given data or to the given default Value; if $variableName
    * is nullable and its value is set to null in the $fields array, then mark it as "set to null" in the
    * $this->openAPINullablesSetToNull array
    *
    * @param string $variableName
    * @param array  $fields
    * @param mixed  $defaultValue
    */
    private function setIfExists(string $variableName, array $fields, $defaultValue): void
    {
        if (self::isNullable($variableName) && array_key_exists($variableName, $fields) && is_null($fields[$variableName])) {
            $this->openAPINullablesSetToNull[] = $variableName;
        }

        $this->container[$variableName] = $fields[$variableName] ?? $defaultValue;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        $allowedValues = $this->getInstrumentTypeAllowableValues();
        if (!is_null($this->container['instrument_type']) && !in_array($this->container['instrument_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'instrument_type', must be one of '%s'",
                $this->container['instrument_type'],
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getInstrumentStatusAllowableValues();
        if (!is_null($this->container['instrument_status']) && !in_array($this->container['instrument_status'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'instrument_status', must be one of '%s'",
                $this->container['instrument_status'],
                implode("', '", $allowedValues)
            );
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets customer_id
     *
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->container['customer_id'];
    }

    /**
     * Sets customer_id
     *
     * @param string|null $customer_id customer_id for which the instrument was saved
     *
     * @return self
     */
    public function setCustomerId($customer_id)
    {
        if (is_null($customer_id)) {
            throw new \InvalidArgumentException('non-nullable customer_id cannot be null');
        }
        $this->container['customer_id'] = $customer_id;

        return $this;
    }

    /**
     * Gets afa_reference
     *
     * @return string|null
     */
    public function getAfaReference()
    {
        return $this->container['afa_reference'];
    }

    /**
     * Sets afa_reference
     *
     * @param string|null $afa_reference cf_payment_id of the successful transaction done while saving instrument
     *
     * @return self
     */
    public function setAfaReference($afa_reference)
    {
        if (is_null($afa_reference)) {
            throw new \InvalidArgumentException('non-nullable afa_reference cannot be null');
        }
        $this->container['afa_reference'] = $afa_reference;

        return $this;
    }

    /**
     * Gets instrument_id
     *
     * @return string|null
     */
    public function getInstrumentId()
    {
        return $this->container['instrument_id'];
    }

    /**
     * Sets instrument_id
     *
     * @param string|null $instrument_id saved instrument id
     *
     * @return self
     */
    public function setInstrumentId($instrument_id)
    {
        if (is_null($instrument_id)) {
            throw new \InvalidArgumentException('non-nullable instrument_id cannot be null');
        }
        $this->container['instrument_id'] = $instrument_id;

        return $this;
    }

    /**
     * Gets instrument_type
     *
     * @return string|null
     */
    public function getInstrumentType()
    {
        return $this->container['instrument_type'];
    }

    /**
     * Sets instrument_type
     *
     * @param string|null $instrument_type Type of the saved instrument
     *
     * @return self
     */
    public function setInstrumentType($instrument_type)
    {
        if (is_null($instrument_type)) {
            throw new \InvalidArgumentException('non-nullable instrument_type cannot be null');
        }
        $allowedValues = $this->getInstrumentTypeAllowableValues();
        if (!in_array($instrument_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'instrument_type', must be one of '%s'",
                    $instrument_type,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['instrument_type'] = $instrument_type;

        return $this;
    }

    /**
     * Gets instrument_uid
     *
     * @return string|null
     */
    public function getInstrumentUid()
    {
        return $this->container['instrument_uid'];
    }

    /**
     * Sets instrument_uid
     *
     * @param string|null $instrument_uid Unique id for the saved instrument
     *
     * @return self
     */
    public function setInstrumentUid($instrument_uid)
    {
        if (is_null($instrument_uid)) {
            throw new \InvalidArgumentException('non-nullable instrument_uid cannot be null');
        }
        $this->container['instrument_uid'] = $instrument_uid;

        return $this;
    }

    /**
     * Gets instrument_display
     *
     * @return string|null
     */
    public function getInstrumentDisplay()
    {
        return $this->container['instrument_display'];
    }

    /**
     * Sets instrument_display
     *
     * @param string|null $instrument_display masked card number displayed to the customer
     *
     * @return self
     */
    public function setInstrumentDisplay($instrument_display)
    {
        if (is_null($instrument_display)) {
            throw new \InvalidArgumentException('non-nullable instrument_display cannot be null');
        }
        $this->container['instrument_display'] = $instrument_display;

        return $this;
    }

    /**
     * Gets instrument_status
     *
     * @return string|null
     */
    public function getInstrumentStatus()
    {
        return $this->container['instrument_status'];
    }

    /**
     * Sets instrument_status
     *
     * @param string|null $instrument_status Status of the saved instrument.
     *
     * @return self
     */
    public function setInstrumentStatus($instrument_status)
    {
        if (is_null($instrument_status)) {
            throw new \InvalidArgumentException('non-nullable instrument_status cannot be null');
        }
        $allowedValues = $this->getInstrumentStatusAllowableValues();
        if (!in_array($instrument_status, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'instrument_status', must be one of '%s'",
                    $instrument_status,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['instrument_status'] = $instrument_status;

        return $this;
    }

    /**
     * Gets created_at
     *
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->container['created_at'];
    }

    /**
     * Sets created_at
     *
     * @param string|null $created_at Timestamp at which instrument was saved.
     *
     * @return self
     */
    public function setCreatedAt($created_at)
    {
        if (is_null($created_at)) {
            throw new \InvalidArgumentException('non-nullable created_at cannot be null');
        }
        $this->container['created_at'] = $created_at;

        return $this;
    }

    /**
     * Gets instrument_meta
     *
     * @return \Cashfree\Model\SavedInstrumentMeta|null
     */
    public function getInstrumentMeta()
    {
        return $this->container['instrument_meta'];
    }

    /**
     * Sets instrument_meta
     *
     * @param \Cashfree\Model\SavedInstrumentMeta|null $instrument_meta instrument_meta
     *
     * @return self
     */
    public function setInstrumentMeta($instrument_meta)
    {
        if (is_null($instrument_meta)) {
            throw new \InvalidArgumentException('non-nullable instrument_meta cannot be null');
        }
        $this->container['instrument_meta'] = $instrument_meta;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int|null $offset Offset
     * @param mixed    $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @link https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed Returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
       return ObjectSerializer::sanitizeForSerialization($this);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Gets a header-safe presentation of the object
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


