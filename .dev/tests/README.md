# Extension checks

Run `php .dev/tests/run.php` with PHP 8.3, DOM and SimpleXML. Set `ITOP_SCHEMA_DIR` to a directory containing `extension.xsd` and the standalone `3.3/itop_design.xsd` from the [iTop schemas](https://rudnerbjoern.github.io/iTop-schema/). CI downloads these into `.tmp/schemas/`. The checks validate the descriptor and the IONOS module XML and exercise release preparation in memory.

## Compiled model integration test

From the sibling iTop development environment, run the isolated test with the Cloud base repository as a dependency:

```powershell
.\scripts\tests\Invoke-Tests.ps1 -Suite Extensions `
    -ExtensionPath ../iTop-br-ionos-cloud-datamodel -ExtensionSuite Integration `
    -Profile Vanilla -DependencyPath ../iTop-br-cloud-datamodel
```

The test checks the IONOS field on a native Cloud, its default and stored values, the sample provider and its public locations, and repeat sample loading with native key reconciliation.
