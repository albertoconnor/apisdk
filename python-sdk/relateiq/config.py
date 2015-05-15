import client as riq
from requests import HTTPError

class Config(object) :
    def __init__(self, integrationType, data=None, isTest=False) :
        """
        :param data: for testing only.
        :return:
        """
        self.isPaused = False
        self.syncCustomEvents = False
        self.events = []

        if data is not None:
            config = data
        else:
            try:
                if isTest:
                    config = riq.get('configs')
                else:
                    config = self.fetchAndMarkAsRunning(integrationType)
            except HTTPError as e:
                print "Failed to fetch config object with status code " + str(e.response.status_code) + \
                      " and message: " + str(e.message) + " and content " + str(e.response.content)
                raise

        self.__dict__.update(config)
        if 'lastRunDuration' in self.meta:
            del self.meta['lastRunDuration']
        if 'numberSuccessfulMappings' in self.meta:
            del self.meta['numberSuccessfulMappings']

    def save(self) :
        return riq.post('configs',{'meta':self.meta,'creds':self.creds,'mappings':self.mappings})

    def fetchAndMarkAsRunning(self, integrationType):
        if integrationType == "CONTACT" or integrationType == "EVENT":
            options = {"integrationType" : integrationType}
            return riq.post('configs/audits', None, options)
        else:
            print "Cannot fetch and mark as running with integration type=" + str(integrationType)
            raise

    def saveAndMarkAsComplete(self, integrationType):
        if integrationType == "CONTACT" or integrationType == "EVENT":
            options = {"integrationType" : integrationType}
            return riq.put('configs/audits', {'meta':self.meta,'creds':self.creds,'mappings':self.mappings}, options)
        else:
            print "Cannot fetch and mark and complete with integration type=" + str(integrationType)
            raise

    def outMappings(self):
        return [m for m in self.mappings if Config.isOut(m)]

    def inMappings(self):
        return [m for m in self.mappings if not Config.isOut(m)]

    @staticmethod
    def isOut(listMapping):
        return listMapping['meta']['direction'] == 'out'

    @staticmethod
    def isAdd(listMapping):
        return listMapping["meta"]["addOrUpdate"] == "add"

    @staticmethod
    #Convert a single list mapping from riqconfig into something more useful:
    #{internal field id : (external field label, external field id)}
    def generateFieldMap(mapping):
        returnDict = {}
        for field in mapping['fields']:
            # TODO : Use an explicitly keyed map (or even better, a proper object) instead of tuples
            # TODO : Pull out relateiqLabel as a constant
            # TODO : Assumes field['internal'] is unique in mapping - We should have a config Validator. Avro?
            returnDict[field['internal']] = (
                field.get('meta', {}).get('relateiqLabel', None),
                field.get('external', None)
            )
        # validate:
        for riqFieldId, (externalFieldLabel, externalFieldId) in returnDict.items():
            if riqFieldId is None or externalFieldLabel is None:
                raise ("invalid field mapping: riqFieldId or externalField None")
        return returnDict